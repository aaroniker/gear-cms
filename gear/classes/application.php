<?php

class application {

    public function __construct($config) {

        //$this->hook for events

        $this->config = $config;
        $this->modules = new moduleManager($this);

    }

    public function boot() {

        hook::run('boot', $this);

        echo $this->content;

    }

}

?>
