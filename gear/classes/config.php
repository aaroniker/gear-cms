<?php

class config {

    static $params = [];
    static $isChange = false;

    static $newEntrys = [];

    public function __construct() {

        self::$params = json_decode(file_get_contents(dir::gear('config.json')), true);

        if(self::get('dev')) {

            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            $less = new lessc;

            $less->setFormatter('compressed');

            try {

                $newCSS = $less->compileFile(dir::less('style.less'));

                $fp = fopen(dir::css('style.css'),"wb");
                fwrite($fp, $newCSS);
                fclose($fp);

            } catch (exception $e) {

                echo message::error($e->getMessage());

            }

        }

    }

    public static function has($name) {

        return isset(self::$params[$name]) || array_key_exists($name, self::$params);

    }

    public static function get($name, $default = null) {

        if(self::has($name)) {
            return self::$params[$name];
        }

        return $default;

    }

    public static function add($name, $value, $toSave = false) {

        self::$params[$name] = $value;

        if($toSave) {
            self::$isChange = true;
            self::$newEntrys[$name] = $value;
        }

    }

    public static function save() {

        if(!self::$isChange) {
            return true;
        }

        $newEntrys = array_merge(self::$params, self::$newEntrys);

        return file_put_contents(dir::gear('config.json'), json_encode($newEntrys, JSON_PRETTY_PRINT));

    }

}

?>
