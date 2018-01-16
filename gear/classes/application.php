<?php

class application {

    public function __construct($config) {

        //$this['hook'] for events

        $this->modules = new moduleManager($this);

    }

    public function start() {

    }

}

?>
