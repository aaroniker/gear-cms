<?php

class application {

    public function __construct($config) {

        $this->config = $config;

        if($this->config->get('system')['debug']) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        }

        $this->hook = new hook();
        $this->modules = new moduleManager($this);

    }

    public function boot() {

        $this->hook::run('boot', $this);

        echo $this->content;

    }

}

?>
