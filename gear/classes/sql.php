<?php

class sql extends FluentPDO {

    protected $prefix = '';

    public function from($table, $primaryKey = null) {
        return parent::from($this->prefix.$table, $primaryKey);
    }

    public function insertInto($table, $values = array()) {
        return parent::insertInto($this->prefix.$table, $values);
    }

    public function update($table, $set = array(), $primaryKey = null) {
        return parent::update($this->prefix.$table, $set, $primaryKey);
    }

    public function deleteFrom($table, $primaryKey = null) {
        return parent::deleteFrom($this->prefix.$table, $primaryKey);
    }

    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function getPrefix() {
        return $this->prefix;
    }

}

?>
