<?php

class router {

    protected $app;

    protected $base;

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

    protected static $methods = [
        'GET',
        'POST',
        'DELETE',
        'PUT',
        'PATCH',
        'OPTIONS'
    ];

    protected $errorCallback;

    public function __construct($app) {

        $this->app = $app;

        $this->base = $this->app->config->get('system')['apiURL'];

    }

    public function __call($method, $params) {

        if(is_null($params) || !in_array(strtoupper($method), self::$methods)) {
            return;
        }

        $route = $params[0];
        $callback = $params[1];
        $errorCallback = isset($params[2]) ? $params[2] : false;

        $this->addRoute($route, $method, $callback, $errorCallback);

        return;

    }

    protected function addRoute($route, $method, $callback, $errorCallback) {

        $data = [
            'route' => str_replace('//', '/', $this->base.$route),
            'method' => strtoupper($method),
            'callback' => $callback,
            'errorCallback' => $errorCallback
        ];

        array_push($this->routes, $data);

        return $data;

    }

    private function runCallback($function, $params = null) {
        if(!is_null($params)) {
            echo call_user_func_array($function, $params);
            return;
        }
        echo call_user_func($function);
    }

    public function run() {

        $method = self::getMethod();
        $foundRoute = false;
        $routes = [];

        foreach($this->routes as $data) {
            array_push($routes, $data['route']);
        }

        if(in_array($this->app->route->route, array_values($routes))) {
            foreach($this->routes as $data) {
                if(self::validMethod($data['method'], $method) && ($data['route'] === $this->app->route->route)) {
                    $foundRoute = true;
                    if($data['errorCallback'] && is_callable($data['errorCallback'])) {
                        $this->errorCallback = $data['errorCallback'];
                    }
                    $this->runCallback($data['callback']);
                    break;
                }
            }
        } else {
            foreach($this->routes as $data) {
                $route = $data['route'];
                if(strpos($route, '{') !== false) {
                    $route = str_replace(array_keys($this->patterns), array_values($this->patterns), $route);
                }
                if(preg_match('#^'.$route.'$#', $this->app->route->route, $matched)) {
                    if(self::validMethod($data['method'], $method)) {
                        $foundRoute = true;
                        array_shift($matched);
                        $newMatched = [];
                        foreach($matched as $key => $value) {
                            if(strstr($value, '/')) {
                                foreach(explode('/', $value) as $k => $v) {
                                    $newMatched[] = trim(urldecode($v));
                                }
                            } else {
                                $newMatched[] = trim(urldecode($value));
                            }
                        }
                        $matched = $newMatched;
                        if($data['errorCallback'] && is_callable($data['errorCallback'])) {
                            $this->errorCallback = $data['errorCallback'];
                        }
                        $this->runCallback($data['callback'], $matched);
                        break;
                    }
                }
            }
        }

        if($foundRoute === false) {
            if(!$this->errorCallback) {
                $this->errorCallback = function() {
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                    return __('URL <strong>%s</strong> not found', [$this->app->route->route]);
                };
            }
            echo call_user_func($this->errorCallback);
        }

    }

    public static function getPost($key = false) {
        $json = json_decode(file_get_contents('php://input'), true);
        $data = (isset($json) && is_array($json)) ? $json : [];
        if($key) {
            return $data[$key];
        }
        return $data;
    }

    protected static function isAjax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    protected static function getHeader() {
        if(function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = [];
        foreach($_SERVER as $name => $value) {
            if(substr($name, 0, 5) == 'HTTP_' || $name === 'CONTENT_TYPE' || $name === 'CONTENT_LENGTH') {
                $headers[str_replace([' ', 'Http'], ['-', 'HTTP'], ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public static function getMethod() {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method === 'POST') {
            $headers = self::getHeader();
            if(isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], ['PUT', 'DELETE', 'PATCH', 'OPTIONS'])) {
                $method = $headers['X-HTTP-Method-Override'];
            } elseif(!empty($_POST['_method'])) {
                $method = strtoupper($_POST['_method']);
            }
        }
        return $method;
    }

    public static function validMethod($data, $method) {
        $valid = false;
        if(strstr($data, '|')) {
            foreach(explode('|', $data) as $value) {
                $valid = self::checkMethods($value, $method);
                if($valid) {
                    break;
                }
            }
        } else {
            $valid = self::checkMethods($data, $method);
        }
        return $valid;
    }

    protected static function checkMethods($value, $method) {
        $valid = false;
        if($value === 'AJAX' && self::isAjax() && $value === $method) {
            $valid = true;
        } elseif($value === 'AJAXP' && self::isAjax() && $method === 'POST') {
            $valid = true;
        } elseif(in_array($value, self::$methods) && ($value === $method || $value === 'ANY')) {
            $valid = true;
        }
        return $valid;
    }

}

?>
