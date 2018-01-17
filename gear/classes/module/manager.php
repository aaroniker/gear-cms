<?php

class moduleManager {

    protected $app;

    protected $modules = [];
    protected $registered = [];
    protected $requiredError = [];

    protected $defaults = [
        'run' => null,
        'config' => [],
        'options' => []
    ];

    public function __construct($app) {
        $this->app = $app;
    }

    public function get($name) {
        return isset($this->modules[$name]) ? $this->modules[$name] : null;
    }

    public function all() {
        return $this->modules;
    }

    public function register($paths, $basePath = null) {

        $subRegister = [];

        foreach($paths as $path) {
            $files = glob($this->checkPath($path, $basePath), GLOB_NOSORT) ?: [];
            foreach($files as $file) {

                if(!is_array($module = include $file) || !isset($module['name'])) {
                    continue;
                }

                $module = array_replace($this->defaults, $module);
                $module['path'] = strtr(dirname($file), '\\', '/');

                if(isset($module['register'])) {
                    foreach((array)$module['register'] as $register) {
                        $subRegister[] = $this->checkPath($register, $module['path']);
                    }
                }

                $this->registered[$module['name']] = $module;

            }
        }

        if($subRegister) {
            $this->register($subRegister);
        }

        return $this;

    }

    public function load($modules = null) {

        $checked = [];

        if(is_string($modules)) {
            $modules = (array)$modules;
        } elseif(is_null($modules)) {
            $modules = array_keys($this->registered);
        }

        foreach((array)$modules as $name) {
            if($this->checkRequired($this->registered[$name])) {

                $module = new module($this->registered[$name]);
                $module->autoload();
                $module->run($this->app);

                $checked[$name] = $module;

            } else {
                $this->requiredError[$name] = $this->registered[$name];
            }
        }

        $this->modules = $checked;

        return $this;

    }

    protected function checkPath($path, $basePath = null) {

        $path = strtr($path, '\\', '/');

        if (!($path[0] == '/' || (strlen($path) > 3 && ctype_alpha($path[0]) && $path[1] == ':' && $path[2] == '/'))) {
            $path = "$basePath/$path";
        }

        return $path;

    }

    protected function checkRequired($module) {

        if(isset($module['required'])) {
            foreach((array)$module['required'] as $required) {
                if(!isset($this->registered[$required])) {
                    return false;
                }
            }
        }

        return true;

    }

}

?>
