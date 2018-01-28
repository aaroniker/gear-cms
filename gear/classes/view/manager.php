<?php

class viewManager {

    protected $app;

    protected $views;
    protected $placeholder;

    protected $global;

    public function __construct($app) {
        $this->app = $app;
    }

    public function get($key, $default = null) {

        if(isset($this->placeholder[$key])) {
            return $this->placeholder[$key];
        }

        return $default;

    }

    public function global($key, $default = null) {

        if(isset($this->global[$key])) {
            return $this->global[$key];
        }

        return $default;

    }

    public function register($controller, $placeholder = null) {

        $content = '';

        if(isset($controller['return']['view']['set']) && is_array($controller['return']['view']['set'])) {
            foreach($controller['return']['view']['set'] as $key => $val) {
                $this->global[$key] = $val;
            }
        }

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
            return $content;
        }

    }

}

?>
