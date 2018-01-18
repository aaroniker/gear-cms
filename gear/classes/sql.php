<?php

class sql extends FluentPDO {

    protected static $pdoObj;
    protected static $sqlObj;

    protected $prefix = '';

    public static function connect($host, $port, $user, $password, $database, $prefix) {

        try {

            self::$pdoObj = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$database.';charset=utf8', $user, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ]);

            self::$sqlObj = new sql(self::$pdoObj);
            self::$sqlObj->setPrefix($prefix);

        } catch(exception $e) {
            echo $e->getMessage();
            return;
        }

        return self::run();

    }

    public static function run($pdo = false) {
        if($pdo) {
            return self::$pdoObj;
        }
        return self::$sqlObj;
    }

    public function from($table, $primaryKey = null) {
        return parent::from($this->prefix.$table, $primaryKey);
    }

    public function insertInto($table, $values = []) {
        return parent::insertInto($this->prefix.$table, $values);
    }

    public function update($table, $set = [], $primaryKey = null) {
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
