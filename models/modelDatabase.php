<?php namespace framework;

/* Model for access data from a database */

class ModelDatabase
{
    public $interface;
    public $database = "";
    public $table = "";
    public $fields = [];
    public $defaultRecords = [];


    public function __construct($host, $user, $password)
    {
        // For now the only supported interface is MySQL
        // Logic can be added here to support other interfaces
        $this->interface = new ModelMySql($host, $user, $password);

        if ($this->interface->connected and $this->database != "" and $this->table != "" and count($this->fields) > 0)
        {
            if (!$this->tableExist())
            {
                $this->createTable();
                foreach ($this->defaultRecords as $record)
                {
                    $this->insertRecord($record);
                }
            }
        }
    }

    /* Generic */

    public function isConnected()
    {
        return $this->interface->connected;
    }

    public function getLastError()
    {
        return $this->interface->lastError;
    }

    /* Records */

    public function selectRecords($options=[])
    {
        return $this->interface->selectRecords($this->database, $this->table, $options);
    }

    public function insertRecord($record)
    {
        return $this->interface->insertRecord($this->database, $this->table, $record);
    }

    public function updateRecord($record, $condition)
    {
        return $this->interface->updateRecord($this->database, $this->table, $record, $condition);
    }

    public function deleteRecord($condition)
    {
        return $this->interface->deleteRecord($this->database, $this->table, $condition);
    }

    public function countRecords($condition="")
    {
        return $this->interface->countRecords($this->database, $this->table, $condition);
    }

    /* Tables */

    public function tableExist()
    {
        return $this->interface->tableExist($this->database, $this->table);
    }

    public function createTable()
    {
        return $this->interface->createTable($this->database, $this->table, $this->fields);
    }

    public function truncateTable()
    {
        return $this->interface->truncateTable($this->database, $this->table);
    }

    public function dropTable()
    {
        return $this->interface->dropTable($this->database, $this->table);
    }

    public function getTables()
    {
        return $this->interface->getTables($this->database);
    }

    public function getFieldsFromDatabaseTable()
    {
        $fields = [];
        $dbFields = $this->interface->getFieldsFromDatabaseTable($this->database, $this->table);
        foreach ($dbFields as $dbField)
        {
            $field = new ModelDatabaseField();
            $field->name($dbField["Field"]);
            $field->type(strtoupper($dbField["Type"]));
            $field->isRequired($dbField["Null"] == "NO");
            $field->default($dbField["Default"]);
            $field->autoIncrement(str_contains($dbField["Extra"], "auto_increment"));
            $field->isKey(str_contains($dbField["Key"], "PRI"));
            $field->isUnique(false);
            $fields[] = $field;
        }
        return $fields;
    }
}
