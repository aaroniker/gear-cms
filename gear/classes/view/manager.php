<?php

class viewManager {

    protected $app;

    protected $views;
    protected $placeholder;

    public function __construct($app) {
        $this->app = $app;
    }

    public function get($placeholder) {

        if(isset($this->placeholder[$placeholder])) {
            return $this->placeholder[$placeholder];
        }

        return false;

    }

    public function register($controller, $placeholder = null) {

        $content = '';

        if(isset($controller['return']['view']['file'])) {

            $viewPath = $controller['modulePath'].'/'.$controller['return']['view']['file'].'.php';

            $view = new view($viewPath, [
                'app' => $this->app,
                'route' => $this->app->route
            ]);

            $content = $view->render();

        }

        if($placeholder) {
            if(isset($this->placeholder[$placeholder])) {
                $this->placeholder[$placeholder] = $this->placeholder[$placeholder].$content;
            } else {
                $this->placeholder[$placeholder] = $content;
            }
        } else {
            echo $content;
        }

    }

}

?>
