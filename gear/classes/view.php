<?php

class view {

    protected $file;
    protected $data;

    public function __construct($file = false, $data = []) {

        $this->data = $data;
        $this->file = $file;

        $this->data['view'] = $this;

    }

    public function render($file = null) {

        $file = ($file) ? $file : $this->file;

        if($file) {

            ob_start();

            extract($this->data);

            include($this->file);

            $content = ob_get_contents();

            ob_end_clean();

            return $content;

        }

        return false;

    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    public function get($key, $default = null) {
        return (isset($this->data[$key])) ? $this->data[$key] : $default;
    }

}

?>
