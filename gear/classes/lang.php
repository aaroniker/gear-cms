<?php

class lang {

    protected $app;

    protected $lang;
    protected $langArray = [];
    protected $allLangs = [];

    public function __construct($app) {

        $this->app = $app;

        $this->setLang($this->app->config->get('system')['lang']);

        return $this;

    }

    public function setLang($lang) {
        if(file_exists(dir::language($lang.'.json'))) {
            $this->lang = $lang;
            $this->loadLang(dir::language($lang.'.json'));
        }
    }

    public function current() {
        return $this->lang;
    }

    public function get($name) {

        if(isset($this->langArray[$name])) {
            return $this->langArray[$name];
        }

        return $name;

    }

    public function getArray() {
        return $this->langArray;
    }

    public function loadLang($file) {

        $file = file_get_contents($file);

        $file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);
        $array = json_decode($file, true);

        return $this->langArray = array_merge((array)$array, $this->langArray);

    }

    public function getAll() {

        if(!count($this->allLangs)) {

            $langs = array_diff(scandir(dir::language()), ['.', '..']);

            foreach($langs as $lang) {

                $lang = str_replace('.json', '', $lang);

                $file = file_get_contents(dir::language($lang.'.json'));
                $file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);
                $array = json_decode($file, true);

                $this->allLangs[$lang] = $array['lang'];

            }

        }

        return $this->allLangs;

    }

}

?>
