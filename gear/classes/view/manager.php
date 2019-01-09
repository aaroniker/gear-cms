<?php

class viewManager {

    protected $app;

    protected $loader;
    protected $twig;
    protected $views;
    protected $placeholder;

    protected $global;

    public function __construct($app) {

        $this->app = $app;

        $this->loader = new Twig_Loader_Filesystem();
        $this->twig = new Twig_Environment($this->loader, [
            'cache' => (($this->app->config->get('system')['cache']) ? dir::cache() : false),
            'debug' => $this->app->config->get('system')['debug']
        ]);

        return $this;

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

        if(isset($controller['return']['view']['global']) && is_array($controller['return']['view']['global'])) {
            foreach($controller['return']['view']['global'] as $key => $val) {
                $this->global[$key] = $val;
            }
        }

        if(isset($controller['return']['view']['file'])) {

            $module = $controller['module'];

            $viewPath = $module->path.'/'.$controller['return']['view']['file'].'.php';

            $view = new view($viewPath, [
                'app' => $this->app,
                'route' => $this->app->route,
                'module' => $module,
                'assets' => $this->app->assets
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
