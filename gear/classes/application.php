<?php

class application {

    public function __construct($config) {

        $this->hook = new hook();
        $this->config = $config;
        $this->modules = new moduleManager($this);

    }

    public function boot() {

        ini_set('display_errors', $this->config->get('system')['debug'] ? 1 : 0);

        $this->hook::run('boot', $this);

        echo $this->content;

    }

}

?>
