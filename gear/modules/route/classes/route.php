<?php

class route {

    protected $app;

    public $controller = false;
    public $method = false;
    public $params = [];

    public $admin = false;

    public $url;

    public function __construct($app) {

        $this->app = $app;
        $this->url = $url = $this->app->config->get('system')['url'];

        $this->splitUrl();

        if($this->controller == $this->app->config->get('system')['adminURL']) {

            $this->splitUrl(1);
            $this->admin = true;

            $this->controller = $this->app->hook::filter('route.controller.setURL', $this->app, $this->controller);

            $this->app->content = $this->includeController();

        } else {

            $this->app->content = 'frontend';

        }

    }

    public function splitUrl($offset = 0) {

        if(type::get('url', 'string', false)) {

            $url = self::getUrl();

            $this->controller = isset($url[0 + $offset]) ? $url[0 + $offset] : '';
            $this->method = isset($url[1 + $offset]) ? $url[1 + $offset] : '';

            unset($url[0 + $offset], $url[1 + $offset]);

            $this->params = (is_array($url)) ? array_values($url) : false;

        }

    }

    public static function getUrl() {

        if(type::get('url', 'string', false)) {
            $url = trim(type::get('url'), '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return false;

    }

    public function includeController() {

        $loaded = false;
        $return = '';

        foreach($this->getAllRoutes() as $path => $route) {

            if(is_array($route) && count($route)) {
                foreach($route as $url => $file) {
                    if($url == '/'.$this->controller) {

                        if(!file_exists($path.'/'.$file['controller'].'.php')) {
                            continue;
                        }

                        include($path.'/'.$file['controller'].'.php');

                        $this->class = basename($file['controller']).'Controller';
                        $this->class = new $this->class();

                        if(method_exists($this->class, $this->method)) {
                            if(is_array($this->params) && count($this->params)) {
                                $return .= call_user_func_array([$this->class, $this->method], $this->params);
                            } else {
                                $return .= $this->class->{$this->method}();
                            }
                        } else {
                            $return .= $this->class->index();
                        }

                        $loaded = true;
                    }
                }
            }

        }

        if(!$loaded) {
            $this->error404();
        }

        return $return;

    }

    public function getAllRoutes() {
        $routes = [];
        foreach($this->app->modules->all() as $module) {
            if(isset($module->options['routes']) && is_array($module->options['routes']) && count($module->options['routes'])) {
                $routes[$module->path] = $module->options['routes'];
            }
        }
        return $routes;
    }

    public function error404() {

        $url = ($this->admin) ? $this->url.'/'.$this->app->config->get('system')['adminURL'] : $this->url;

        header('location: '.$url);
        exit();

    }

}

?>
