<?php

class controller {

    public $model = false;

    function __construct() {

    }

    private function __loadModel() {

        $this->model = new Model(db());

    }

}
