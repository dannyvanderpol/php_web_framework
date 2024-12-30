<?php namespace framework;

/* Model for access data from a database table */

class ModelDatabaseTable
{
    public $interface;
    public $database = "";
    public $table = "";
    public $fields = [];


    public function __construct($host, $user, $password)
    {
        // For now the only supported interface is MySQL
        // Logic can be added here to support other interfaces
        $this->interface = new ModelMySql($host, $user, $password);

        if ($this->interface->connected)
        {
            if (!$this->interface->tableExist($this->database, $this->table))
            {
                $this->interface->createTable($this->database, $this->table, $this->fields);
            }
        }
    }

    public function getLastError()
    {
        return $this->interface->lastError;
    }

    public function getRecords()
    {
        return $this->interface->getRecords($this->database, $this->table);
    }

    public function addRecord($record)
    {
        return $this->interface->addRecord($this->database, $this->table, $record);
    }

    public function updateRecord($record, $condition)
    {
        return $this->interface->updateRecord($this->database, $this->table, $record, $condition);
    }

    public function deleteRecord($condition)
    {
        return $this->interface->deleteRecord($this->database, $this->table, $condition);
    }
}
