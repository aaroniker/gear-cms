<?php

class route {

    protected $app;

    protected $routes = [];

    public $controller = false;
    public $method = false;
    public $params = [];

    public $admin = false;

    public $url;
    public $route;

    public function __construct($app) {

        $this->app = $app;
        $this->url = $this->app->config->get('system')['url'];

        $url = self::getUrlStatic();

        if(is_array($url) && $url[0] == $this->app->config->get('system')['adminURL']) {

            $this->admin = true;
            unset($url[0]);
            $this->route = implode('/', $url);

        } else {

            echo 'frontend';

        }

        $this->app->isAdmin = $this->admin;

    }

    /*
    public function splitUrl($offset = 0) {

        if($getUrl = type::get('url', 'string', false)) {

            $url = self::getUrlStatic();

            $this->controller = isset($url[0 + $offset]) ? $url[0 + $offset] : '';
            $this->method = isset($url[1 + $offset]) ? $url[1 + $offset] : '';

            unset($url[0 + $offset], $url[1 + $offset]);

            $this->params = (is_array($url)) ? array_values($url) : false;

            var_dump($this->params);

            $this->route = str_replace($this->app->config->get('system')['adminURL'].'/', '', $getUrl);

        }

    }
    */

    public function includeController() {

        $loaded = false;

        foreach($this->getAllRoutes() as $path => $module) {

            if(is_array($module->options['routes']) && count($module->options['routes'])) {
                foreach($module->options['routes'] as $url => $array) {
                    //if(basename($array['controller']) == $this->controller && (bool)preg_match('#^'.str_replace('*', '.*', $url).'$#', $this->route)) {
                    if((bool)preg_match('#^'.str_replace('*', '.*', $url).'$#', $this->route)) {

                        $this->controller = $array['controller'];

                        if(!file_exists($path.'/'.$array['controller'].'.php')) {
                            continue;
                        }

                        $urlArr = [];
                        $name = str_replace(['(/*)?', '/*'], '', $url).'/';
                        if(substr($this->route, 0, strlen($name)) == $name) {
                            $urlArr = explode('/', substr($this->route, strlen($name)));
                            if(isset($urlArr[0])) {
                                $this->method = $urlArr[0];
                                unset($urlArr[0]);
                            }
                            $this->params = $urlArr;
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

        if(!$loaded && $this->app->isAdmin) {
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
                    $this->routes[$url] = $array;
                }
            }
        }
        return $routes;
    }

    public function redirect($url, $array = []) {
        $this->getAllRoutes();
        if(isset($this->routes[$url]) && $this->fullURL() != $this->getLink($url, $array) && !ajax::is()) {
            header('location: '.$this->getLink($url, $array));
            exit();
        }
    }

    public function fullURL() {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function getLink($url, $array = []) {

        $this->getAllRoutes();
        $base = ($this->admin) ? $this->url.'/'.$this->app->config->get('system')['adminURL'] : $this->url;

        if(isset($this->routes[$url])) {
            $params = (is_array($array) && count($array)) ? '/'.implode('/', $array) : '';
            return $base.'/'.$url.$params;
        }

        return $base;

    }

    public function getURL($url) {

        $base = ($this->admin) ? $this->url.'/'.$this->app->config->get('system')['adminURL'] : $this->url;

        return $base.'/'.$url;

    }

    public function error404() {

        $url = ($this->admin) ? $this->url.'/'.$this->app->config->get('system')['adminURL'] : $this->url;
        if(!ajax::is()) {
            header('location: '.$url);
            exit();
        }

    }

    public static function getUrlStatic() {

        if(type::get('url', 'string', false)) {
            $url = trim(type::get('url'), '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return false;

    }

}

?>
