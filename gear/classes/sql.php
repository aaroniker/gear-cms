<?php

class sql extends FluentPDO {

    protected static $sqlObj;
    protected static $pdoObj;

    protected $prefix = '';

    public static function connect($host, $user, $password, $database, $prefix) {

        $return = true;

        try {

            self::$pdoObj = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $user, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ]);

            self::$sqlObj = new sql(self::$pdoObj);
            self::$sqlObj->setPrefix($prefix);

        } catch(exception $e) {
            echo message::getMessage($e->getMessage(), 'error');
            $return = false;
        }

        return $return;

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
