<?php

class block {

    protected static $allBlocks = [];

    protected $file;
    protected $config = [];

    public function __construct($file) {

        $this->file = file_get_contents(dir::blocks($file));

        return $this;

    }

	public static function getAll() {

        if(!count(self::$allBlocks)) {

            $blocks = array_diff(scandir(dir::blocks()), ['.', '..']);

            foreach($blocks as $file) {

                $block = new block($file);

                self::$allBlocks[] = [
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
