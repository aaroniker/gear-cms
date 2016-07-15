<?php

class controller {

    public $model = false;

    function __construct() {

        #$this->loadModel();

    }

    private function __loadModel() {
		
        $this->model = new Model(db());
    
	}
	
}
