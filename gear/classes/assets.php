<?php

class assets {

    protected $app;

    protected $css = [];
    protected $js = [];

    protected $base = '';

    public function __construct($app) {

        $this->app = $app;

        $this->base = $this->app->config->get('system')['url'];

    }

    public function addCSS($file) {
        $this->css[] = [
            'file' => $this->getPath($file)
        ];
    }

    public function addJS($file, $position = 'normal') {
        $this->js[$position][] = [
            'file' => $this->getPath($file)
        ];
    }

    public function getCSS() {

        $return = '';
        $version = '';

        if($this->app->config->get('system')['debug']) {
            $version = '?v'.time();
        }

        foreach($this->css as $css) {
            $return .= '<link rel="stylesheet" href="'.$css['file'].$version.'">'.PHP_EOL;
        }

        return $return;

    }

    public function getJS($position = 'normal') {

        $return = '';
        $version = '';

        if($this->app->config->get('system')['debug']) {
            $version = '?v'.time();
        }

        if(isset($this->js[$position])) {
            foreach($this->js[$position] as $js) {
                $return .= '<script src="'.$js['file'].$version.'"></script>'.PHP_EOL;
            }
        }

        return $return;

    }

    public function getIcon($path) {
        if(file_exists($this->getPath($path, null, true))) {
            return file_get_contents($this->getPath($path, null, true));
        }
        return '';
    }

    protected function getPath($path, $module = null, $include = false) {

        $module = ($module) ? $module : $this->app->currentModule;

        if(strpos($path, '~') !== false && isset($module->path)) {
            if($include) {
                return str_replace('~', $module->path, $path);
            }
            return $this->base.str_replace('~', strstr($module->path, '/gear/'), $path);
        }

        return $this->base.$path;

    }

    public function get($path, $module = null) {
        return $this->getPath($path, $module);
    }

}

?>
