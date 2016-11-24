<?php

class block {

    protected $file;
    protected $config = [];
    protected static $allBlocks = [];

    public function __construct($file) {

        $file = dir::blocks($file);

        if(file_exists($file)) {

            $this->file = file_get_contents($file);

            return $this;

        }

        return false;

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
