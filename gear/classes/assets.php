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

        foreach($this->css as $css) {
            $return .= '<link rel="stylesheet" href="'.$css['file'].'">'.PHP_EOL;
        }

        return $return;

    }

    public function getJS($position = 'normal') {

        $return = '';

        if(isset($this->js[$position])) {
            foreach($this->js[$position] as $js) {
                $return .= '<script src="'.$js['file'].'"></script>'.PHP_EOL;
            }
        }

        return $return;

    }

    protected function getPath($path) {
        if(strpos($path, '~') !== false && isset($this->app->currentModule->path)) {
            return $this->base.str_replace('~', strstr($this->app->currentModule->path, '/gear/'), $path);
        }
        return $this->base.$path;
    }

}

?>
