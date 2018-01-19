<?php

class config {

    protected $params = [];

    protected $file = '';
    protected $fileParams = [];
    protected $isChange = false;

    protected $newEntrys = [];

    public function __construct($file) {

        $this->file = $file;

        $this->params = json_decode(file_get_contents($this->file), true);
        $this->fileParams = $this->params;

    }

    public function has($name) {
        return isset($this->params[$name]) || array_key_exists($name, $this->params);
    }

    public function get($name, $default = null) {

        if($this->has($name)) {
            return $this->params[$name];
        }

        return $default;

    }

    public function add($name, $value, $toSave = false) {

        $this->params[$name] = $value;

        if($toSave) {
            $this->isChange = true;
            $this->newEntrys[$name] = $value;
        }

    }

    public function save() {

        if(!$this->isChange) {
            return true;
        }

        $newEntrys = array_merge($this->fileParams, $this->newEntrys);

        return file_put_contents($this->file, json_encode($newEntrys, JSON_PRETTY_PRINT));

    }

}

?>
