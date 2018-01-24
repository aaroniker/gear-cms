<?php

class view {

    protected $app;

    public function __construct($app) {

        $this->app = $app;

    }

    public function render($controller) {

        if(isset($controller['return']['view']['file'])) {

            $viewPath = $controller['modulePath'].'/'.$controller['return']['view']['file'].'.php';

            $view = $this;
            $app = $this->app;

            include($viewPath);

        }

    }

    public function show($id) {

    }

    public function style($file) {

    }

    public function script($file) {

    }

}

?>
