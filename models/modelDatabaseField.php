<?php namespace framework;

class ModelDatabaseField
{
    // Mandatory
    public $name = "";
    public $type = "";
    public $isRequired = true;
    // Optional
    public $default = null;
    public $autoIncrement = false;
    public $isKey = false;
    public $isUnique = false;


    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function isRequired($isRequired)
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function default($default)
    {
        $this->default = $default;
        return $this;
    }

    public function autoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;
        return $this;
    }

    public function isKey($isKey)
    {
        $this->isKey = $isKey;
        return $this;
    }

    public function isUnique($isUnique)
    {
        $this->isUnique = $isUnique;
        return $this;
    }
}
