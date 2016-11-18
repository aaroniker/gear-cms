<?php

class lang {

    static $lang;
    static $langArray = [];
    static $allLangs = [];

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

    static public function getAll() {

        if(!count(self::$allLangs)) {

            $langs = array_diff(scandir(dir::language()), ['.', '..']);

            foreach($langs as $lang) {

                $lang = str_replace('.json', '', $lang);

                $file = file_get_contents(dir::language($lang.'.json'));
                $file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);
                $array = json_decode($file, true);

                self::$allLangs[$lang] = $array['lang'];

            }

        }

        return self::$allLangs;

    }

}

?>
