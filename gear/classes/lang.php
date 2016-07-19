<?php

class lang {

    static $lang;
    static $langArray = [];

    static public function setLang($lang = 'de') {

        if(file_exists(dir::language($lang.'.json'))) {

            self::$lang = $lang;
            self::loadLang(dir::language($lang.'.json'));

        }

    }

    static public function get($name) {

        if(isset(self::$langArray[$name])) {
            return self::$langArray[$name];
        }

        return $name;

    }

    static public function loadLang($file) {

        $file = file_get_contents($file);

        $file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);
        $array = json_decode($file, true);

        self::$langArray = array_merge((array)$array, self::$langArray);

        return self::$langArray = $array;

    }

}

?>
