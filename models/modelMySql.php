<?php namespace framework;

class ModelMySql extends \mysqli
{
    public $log;
    public $connected;
    public $lastError;


    public function __construct($host, $user, $password)
    {
        $this->log = new ModelLogger("mySql");
        // Prevent any fatal errors, so we can handle the errors ourselves
        mysqli_report(MYSQLI_REPORT_ERROR);
        $this->connected = false;
        $this->lastError = "";
        set_error_handler([$this, "handleError"]);
        parent::__construct($host, $user, $password);
        $this->connected = ($this->connect_errno == 0);
        restore_error_handler();
    }

    public function selectRecords($database, $table, $options=[])
    {
        $filter = arrayGet($options, "filter", "");
        $order = arrayGet($options, "order", "");
        $query = "SELECT * FROM {$database}.{$table}";
        if ($filter != "")
        {
            $query .= " WHERE {$filter}";
        }
        if ($order != "")
        {
            $query .= " ORDER BY {$order}";
        }
        return $this->executeQuery($query);
    }

    public function insertRecord($database, $table, $record)
    {
        $fields = array_keys($record);
        $values = array_values($record);
        $query = "INSERT INTO {$database}.{$table} (";
        $query .= join(", ", $fields) . ") VALUES (";
        $query .= join(", ", array_fill(0, count($values), "?")) . ")";
        return $this->executeQuery($query, $values);
    }

    public function updateRecord($database, $table, $record, $condition)
    {
        $fields = array_map(function ($x) { return "{$x} = ?"; }, array_keys($record));
        $values = array_values($record);
        $query = "UPDATE {$database}.{$table} SET " . join(", ", $fields);
        $query .= " WHERE {$condition}";
        return $this->executeQuery($query, $values);
    }

    public function deleteRecord($database, $table, $condition)
    {
        $query = "DELETE FROM {$database}.{$table} WHERE {$condition}";
        return $this->executeQuery($query);
    }

    public function tableExist($database, $table)
    {
        $query = "SHOW TABLES FROM {$database} LIKE '{$table}'";
        $result = $this->executeQuery($query);
        return ($result !== false and count($result) > 0);
    }

    public function createTable($database, $table, $fields)
    {
        $keys = [];
        $unique = [];
        $query = "CREATE TABLE {$database}.{$table} (";
        foreach ($fields as $field)
        {
            $query .= "\n  ";
            $query .= "{$field->name} {$field->type} " . ($field->isRequired ? "NOT NULL" : "NULL");
            $query .= (($field->default !== null or !$field->isRequired) ? " DEFAULT " . $this->toQuery($field->default) : "");
            $query .= ($field->autoIncrement ? " AUTO_INCREMENT" : "");
            $query .= ",";
            if ($field->isKey)
            {
                $keys[] = $field->name;
            }
            if ($field->isUnique)
            {
                $unique[] = $field->name;
            }
        }
        if (count($keys) > 0)
        {
            $query .= "\n  PRIMARY KEY (" . implode(",", $keys) . "),";
        }
        if (count($unique) > 0)
        {
            $query .= "\n  UNIQUE (" . implode(",", $unique) . "),";
        }
        $query = trim($query, ",") . "\n)";
        return $this->executeQuery($query);
    }

    public function truncateTable($database, $table)
    {
        return $this->executeQuery("TRUNCATE {$database}.{$table}");
    }

    public function dropTable($database, $table)
    {
        return $this->executeQuery("DROP TABLE {$database}.{$table}");
    }

    public function executeQuery($query, $params=null)
    {
        $this->lastError = "";
        set_error_handler([$this, "handleError"]);
        $result = parent::execute_query($query, $params);
        restore_error_handler();
        if ($this->errno != 0)
        {
            $this->log->writeMessage("Error when exectuting query:\n{$query}");
            if ($params != null)
            {
                $this->log->writeMessage("Parameters:");
                $this->log->writeDataArray($params);
            }
        }
        // Return either records or the boolean result
        if (!is_bool($result))
        {
            $records = [];
            while ($row = $result->fetch_assoc()) { $records[] = $row; }
            return $records;
        }
        return $result;
    }

    public function toQuery($value)
    {
        if (is_string($value) and strtolower($value) != "null")
        {
            $value = "'{$this->real_escape_string($value)}'";
        }
        return strval($value ?? "null");
    }

    // Error handler
    private function handleError($errno, $errstr, $errfile, $errline)
    {
        $this->lastError = $errstr;
        $this->log->writeMessage("Errno {$errno}: {$errstr}");
        $this->log->writeMessage("{$errfile} line {$errline}");
        return true;
    }

    // Overrides
    public function real_query($query) : bool
    {
        $this->log->writeMessage("Call to 'real_query()' is not preferred, use 'executeQuery()' instead");
        return $this->executeQuery($query);
    }

    public function query($query, $resultMode=MYSQLI_STORE_RESULT) : \mysqli_result | bool
    {
        $this->log->writeMessage("Call to 'query()' is not preferred, use 'executeQuery()' instead");
        return $this->executeQuery($query);
    }
}
