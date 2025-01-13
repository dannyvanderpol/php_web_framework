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
                    $this->addRecord($record);
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
}
