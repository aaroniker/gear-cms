<?php

class block {

    protected $file;
    protected $config = [];
    protected static $allBlocks = [];
    protected static $installedBlocks = [];

    public function __construct($file) {

        $file = dir::blocks($file);

        if(file_exists($file)) {

            $this->file = file_get_contents($file);

            return $this;

        }

        return false;

    }

    public static function generateCSS($blocks = []) {

        $css = '';

        foreach($blocks as $file) {

            $block = new self($file.'.block');

            if($block) {
                $css .= $block->getCSS();
            }

        }

        if($css) {

            $less = new lessc;
            $less->setFormatter('compressed');

            try {
                $css = $less->compile($css);
            } catch(exception $e) {

            }

        }

        $fp = fopen(dir::tmp('css/blocks.css'),"wb");
        fwrite($fp, $css);
        fclose($fp);

    }

	public static function getAll() {

        if(!count(self::$allBlocks)) {

            $active = unserialize(option::get('blocks'));
            $active = (!is_array($active)) ? [] : $active;

            $blocks = array_diff(scandir(dir::blocks()), ['.', '..']);

            foreach($blocks as $file) {

                $block = new block($file);

                self::$allBlocks[] = [
                    'id' => str_replace('.block', '', $file),
                    'active' => in_array(str_replace('.block', '', $file), $active),
                    'name' => $block->getInfo('name'),
                    'description' => $block->getInfo('description')
                ];

            }

        }

        return self::$allBlocks;

	}

	public static function getInstalled() {

        if(!count(self::$installedBlocks)) {

            $active = unserialize(option::get('blocks'));
            $active = (!is_array($active)) ? [] : $active;

            foreach($active as $file) {

                $block = new block($file.'.block');

                self::$installedBlocks[] = [
                    'id' => $file,
                    'name' => $block->getInfo('name'),
                    'description' => $block->getInfo('description')
                ];

            }

        }

        return self::$installedBlocks;

	}

    public function getInfo($key = false) {

        $info = $this->filterFile('[info]', '[/info]');
        $info = json_decode($info, true);

        if($key && isset($info[$key])) {
            return $info[$key];
        }

        return $info;

    }

    public function getContent() {
        return $this->filterFile('[content]', '[/content]');
    }

    public function getCSS() {
        return $this->filterFile('[css]', '[/css]');
    }

    protected function filterFile($start, $end) {

        $string = ' '.$this->file;
        $ini = strpos($string, $start);

        if($ini == 0) {
            return '';
        }

        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);

    }

}

?>
