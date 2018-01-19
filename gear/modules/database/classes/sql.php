<?php

class sql {

    protected $app;
    protected $module;

    protected $pdoObj;
    protected $sqlObj;

    protected $prefix = '';

    public function __construct($app, $module) {

        $this->app = $app;
        $this->module = $module;

        $db = $this->module->config('database');

        $this->connect($db['host'], $db['port'], $db['user'], $db['password'], $db['name'], $db['prefix']);

        return $this;

    }

    public function connect($host, $port, $user, $password, $name, $prefix) {

        try {

            $this->pdoObj = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$name.';charset=utf8', $user, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ]);

            $this->sqlObj = new FluentPDO($this->pdoObj);
            $this->setPrefix($prefix);

        } catch(exception $e) {
            echo $e->getMessage();
            return;
        }

        return $this;

    }

    public function pdoObj() {
        return $this->pdoObj;
    }

    public function from($table, $primaryKey = null) {
        return $this->sqlObj->from($this->prefix.$table, $primaryKey);
    }

    public function insertInto($table, $values = []) {
        return $this->sqlObj->insertInto($this->prefix.$table, $values);
    }

    public function update($table, $set = [], $primaryKey = null) {
        return $this->sqlObj->update($this->prefix.$table, $set, $primaryKey);
    }

    public function deleteFrom($table, $primaryKey = null) {
        return $this->sqlObj->deleteFrom($this->prefix.$table, $primaryKey);
    }

    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function getPrefix() {
        return $this->prefix;
    }

    public function getVersion() {
        return $this->pdoObj()->query('select version()')->fetchColumn();
    }

}

?>
