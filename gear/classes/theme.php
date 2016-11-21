<?php

class theme {

    protected $config = [];
    protected static $allThemes = [];

    public static $cssFiles = [];
    public static $jsFiles = [];
    public static $jsCode = [
        'jquery' => [],
        'all' => []
    ];

    public function __construct($name) {

        $file = dir::themes('theme.json', $name);

        if(file_exists($file)) {
            $this->config = json_decode(file_get_contents($file), true);
        }

    }

    public static function getAll() {

        if(!count(self::$allThemes)) {

            $themes = array_diff(scandir(dir::themes()), ['.', '..']);

            foreach($themes as $dir) {

                $theme = new self($dir);

                self::$allThemes[$dir] = $theme->getConfig();

            }

        }

        return self::$allThemes;

    }

    public function getConfig() {
        return $this->config;
    }

    public static function addCSS($css_file) {
        self::$cssFiles[] = [
            'file' => $css_file
        ];
    }

    public static function addJs($js_file, $type = 'footer') {
        self::$jsFiles[$type][] = [
            'file' => $js_file
        ];;
    }

    public static function addJsCode($code, $jquery = true) {
        self::$jsCode[($jquery) ? 'jquery' : 'all'][] = $code;
    }

    public static function getCSS() {

        $return = '';

        foreach(self::$cssFiles as $css) {
            $return .= '<link rel="stylesheet" href="'.$css['file'].'">'.PHP_EOL;
        }

        return $return;

    }

    public static function getJS($type = 'footer') {

        $return = '';

        if(isset(self::$jsFiles[$type])) {
            foreach(self::$jsFiles[$type] as $js) {
                $return .= '<script src="'.$js['file'].'"></script>'.PHP_EOL;
            }
        }

        if($type == 'footer') {
            if(isset(self::$jsFiles['vue'])) {
                foreach(self::$jsFiles['vue'] as $js) {
                    $return .= '<script src="'.$js['file'].'"></script>'.PHP_EOL;
                }
            }
        }

        return $return;

    }

    public static function getJSCode() {

        $js = '$(document).ready(function() {';

        foreach(self::$jsCode['jquery'] as $code) {
            $js .= $code;
        }

        $js .= '});';

        foreach(self::$jsCode['all'] as $code) {
            $js .= $code;
        }

        $return = '<script>'.PHP_EOL;
        $return .= filter::compress($js).PHP_EOL;
        $return .= '</script>';

        return $return;

    }

}

?>
