<?php

class application {

    public function __construct($config) {

        $this->hook = new hook();
        $this->config = $config;
        $this->modules = new moduleManager($this);

    }

    public function boot() {

        $this->hook::run('boot', $this);

        echo $this->content;

    }

}

?>
