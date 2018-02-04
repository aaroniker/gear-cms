<?php

class lang {

    protected $app;
    protected $module;

    protected $lang;
    protected $langArray = [];
    protected $allLangs = [];

    public function __construct($app, $module) {

        $this->app = $app;
        $this->module = $module;

        $this->setLang($this->module->config('default'));

        return $this;

    }

    public function setLang($lang) {
        if(file_exists($this->module->path.'/'.$this->module->config('dir').'/'.$lang.'.json')) {
            $this->lang = $lang;
            $this->loadLang($this->module->path.'/'.$this->module->config('dir').'/'.$lang.'.json');
        }
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

        $this->langArray = array_merge((array)$array, $this->langArray);

        return $this->langArray = $array;

    }

    public function getAll() {

        if(!count($this->allLangs)) {

            $langs = array_diff(scandir($this->module->path.'/'.$this->module->config('dir')), ['.', '..']);

            foreach($langs as $lang) {

                $lang = str_replace('.json', '', $lang);

                $file = file_get_contents($this->module->path.'/'.$this->module->config('dir').'/'.$lang.'.json');
                $file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);
                $array = json_decode($file, true);

                $this->allLangs[$lang] = $array['lang'];

            }

        }

        return $this->allLangs;

    }

}

?>
