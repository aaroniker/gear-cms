<?php

class route {

    protected $app;

    protected $routes = [];

    public $controller = false;
    public $method = false;
    public $params = [];

    public $admin = false;

    public $url;

    public function __construct($app) {

        $this->app = $app;
        $this->url = $this->app->config->get('system')['url'];

        $this->splitUrl();

        if($this->controller == $this->app->config->get('system')['adminURL']) {

            $this->splitUrl(1);
            $this->admin = true;

        } else {

            echo 'frontend';

        }

        $this->app->admin = $this->admin;

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

        foreach($this->getAllRoutes() as $path => $module) {

            if(is_array($module->options['routes']) && count($module->options['routes'])) {
                foreach($module->options['routes'] as $url => $array) {
                    $this->routes[$array['name']] = $url;
                    if($url == '/'.$this->controller) {

                        if(!file_exists($path.'/'.$array['controller'].'.php')) {
                            continue;
                        }

                        include($path.'/'.$array['controller'].'.php');

                        $this->class = basename($array['controller']).'Controller';
                        $this->class = new $this->class($this->app);

                        if(method_exists($this->class, $this->method)) {
                            if(is_array($this->params) && count($this->params)) {
                                return [
                                    'return' => call_user_func_array([$this->class, $this->method], $this->params),
                                    'module' => $module
                                ];
                            } else {
                                return [
                                    'return' => $this->class->{$this->method}(),
                                    'module' => $module
                                ];
                            }
                        } else {
                            return [
                                'return' => $this->class->index(),
                                'module' => $module
                            ];
                        }

                        $loaded = true;
                    }
                }
            }

        }

        if(!$loaded && $this->app->admin) {
            $this->error404();
        }

        return [];

    }

    public function getAllRoutes() {
        $routes = [];
        foreach($this->app->modules->all() as $module) {
            if(isset($module->options['routes']) && is_array($module->options['routes']) && count($module->options['routes'])) {
                $routes[$module->path] = $module;
                foreach($module->options['routes'] as $url => $array) {
                    $this->routes[$array['name']] = $url;
                }
            }
        }
        return $routes;
    }

    public function redirect($name, $array = []) {
        $this->getAllRoutes();
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if(isset($this->routes[$name]) && $actual_link != $this->getLink($name, $array)) {
            header('location: '.$this->getLink($name, $array));
            exit();
        }
    }

    public function getLink($name, $array = []) {

        $this->getAllRoutes();
        $url = ($this->admin) ? $this->url.'/'.$this->app->config->get('system')['adminURL'] : $this->url;

        if(isset($this->routes[$name])) {
            $params = (is_array($array) && count($array)) ? '/'.implode('/', $array) : '';
            return $url.$this->routes[$name].$params;
        }

        return $url;

    }

    public function error404() {

        $url = ($this->admin) ? $this->url.'/'.$this->app->config->get('system')['adminURL'] : $this->url;

        header('location: '.$url);
        exit();

    }

}

?>
