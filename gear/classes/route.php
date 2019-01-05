<?php

class route {

    protected $app;

    protected $routes = [];

    protected $patterns = [
        '{a}' => '([^/]+)',
        '{d}' => '([0-9]+)',
        '{i}' => '([0-9]+)',
        '{s}' => '([a-zA-Z]+)',
        '{w}' => '([a-zA-Z0-9_]+)',
        '{u}' => '([a-zA-Z0-9_-]+)',
        '{*}' => '(.*)',
        '{/*}' => '(/.*)?'
    ];

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
        $this->route = (is_array($url)) ? '/'.implode('/', $url) : '/';

        if(is_array($url) && $url[0] == $this->app->config->get('system')['adminURL']) {

            $this->admin = true;
            unset($url[0]);
            $this->route = '/'.implode('/', $url);

        } else {

            //frontend

        }

        $this->app->isAdmin = $this->admin;

    }

    public function includeController() {

        $loaded = false;

        $this->getAllRoutes();

        foreach($this->routes as $key => $array) {

            if(strpos($array['url'], '{') !== false) {
                $array['url'] = str_replace(array_keys($this->patterns), array_values($this->patterns), $array['url']);
            }

            if((bool)preg_match('#^'.$array['url'].'$#', $this->route)) {

                $this->controller = $array['controller'];

                if(!file_exists($array['module']->path.'/'.$array['controller'].'.php')) {
                    continue;
                }

                $urlArr = [];
                $key = $key.'/';
                if(substr($this->route, 0, strlen($key)) == $key) {
                    $urlArr = explode('/', substr($this->route, strlen($key)));
                    if(isset($urlArr[0])) {
                        $this->method = $urlArr[0];
                        unset($urlArr[0]);
                    }
                    $this->params = $urlArr;
                }

                include($array['module']->path.'/'.$array['controller'].'.php');

                $this->class = basename($array['controller']).'Controller';
                $this->class = new $this->class($this->app);

                if(method_exists($this->class, $this->method)) {
                    if(is_array($this->params) && count($this->params)) {
                        return [
                            'return' => call_user_func_array([$this->class, $this->method], $this->params),
                            'module' => $array['module']
                        ];
                    } else {
                        return [
                            'return' => $this->class->{$this->method}(),
                            'module' => $array['module']
                        ];
                    }
                } else {
                    return [
                        'return' => $this->class->index(),
                        'module' => $array['module']
                    ];
                }

                $loaded = true;
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
                    $key = str_replace(array_keys($this->patterns), '', $url);
                    $this->routes[$key] = array_merge($array, [
                        'url' => $url,
                        'module' => $module
                    ]);
                }
            }
        }
        return $routes;
    }

    public function redirect($url, $array = []) {
        $this->getAllRoutes();
        if(isset($this->routes[$url]) && $this->fullURL() != $this->getLink($url, $array)) {
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
            return $base.$url.$params;
        }

        return $base;

    }

    public function getURL($url) {

        $base = ($this->admin) ? $this->url.'/'.$this->app->config->get('system')['adminURL'] : $this->url;

        return $base.$url;

    }

    public function error404() {

        $url = ($this->admin) ? $this->url.'/'.$this->app->config->get('system')['adminURL'] : $this->url;

        header('location: '.$url);
        exit();

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
