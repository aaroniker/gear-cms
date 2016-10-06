<?php

class block {

    protected static $allBlocks = [];

    protected $config = [];

    public function __construct() {

    }

	public static function getAll() {

        if(!count(self::$allBlocks)) {

            $blocks = array_diff(scandir(dir::blocks()), ['.', '..']);

            foreach($blocks as $file) {

                self::$allBlocks[] = $file;

            }

        }

        return self::$allBlocks;

	}

}

?>
