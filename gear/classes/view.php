<?php

class view {

    protected $app;

    public function __construct($app) {

        $this->app = $app;

    }

    public function render($controller) {

        if(isset($controller['return']['view']['file'])) {

            $viewPath = $controller['modulePath'].'/'.$controller['return']['view']['file'].'.php';

            ob_start();

            $view = $this;
            $app = $this->app;
            $route = $this->app->route;

            include($viewPath);

            $content = ob_get_contents();

            ob_end_clean();

            return $content;

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
