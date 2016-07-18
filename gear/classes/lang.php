<?php

class lang {

    static $lang;
    static $langs = [];
    static $default = [];
    static $defaultLang = 'de';

    static public function setLang($lang = 'de') {

        if(file_exists(dir::language($lang.'.json'))) {

            self::$lang = $lang;
            self::loadLang(dir::language(self::getLang()));

        }

    }

    static public function get($name, $lang = false) {

        if($lang)
            return $lang[$name];

        if(isset(self::$langs[$name])) {
            return self::$langs[$name];
        }

        if(isset(self::$default[$name])) {
            return self::$default[$name];
        }

        return $name;

    }

    static public function getLang() {

        return self::$lang;

    }

    static public function getDefaultLang() {

        return self::$defaultLang;

    }

    static public function loadLang($file, $defaultLang = false) {

        $file = file_get_contents($file.'.json');

        $file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);
        $array = json_decode($file, true);

        if(!$defaultLang) {
            self::$langs = array_merge((array)$array, self::$langs);
        } else {
            self::$default = array_merge((array)$array,self:: $default);
        }

        return self::$langs = $array;

    }

    static public function setDefault() {

        $file = dir::language(self::getDefaultLang());

        self::loadLang($file, true);

    }

}

?>
