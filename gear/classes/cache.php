<?php

class cache {

    static protected $cache = false;
    static protected $time = 100;

    static public function setCache($bool) {
        self::$cache = (bool)$bool;
    }

    static public function getFileName($id, $table) {
        return md5($id.$table).'.cache';
    }

    static public function deleteFile($file) {
        return unlink(dir::cache($file));
    }

    static public function exist($file, $time = false) {

        if($time === false) {
            $time = self::$time;
        }

        if(file_exists(dir::cache($file))) {

            if((time() - filemtime(dir::cache($file))) >= $time) {
                self::deleteFile($file);
                clearstatcache();
                return false;
            }

            clearstatcache();

            return true;

        }

        return false;
    }

    static public function write($content, $file) {

        if(self::$cache === true) {
            if(!file_put_contents(dir::cache($file), $content, LOCK_EX)) {
                return false;
            }
        }

        return true;

    }

    static public function read($file) {
        return file_get_contents(dir::cache($file));
    }

    static public function clear($folder = '') {

        if($dir =  opendir(dir::cache($folder))) {

            while (($file = readdir($dir)) !== false) {

                if(is_file($file)) {
                    self::deleteFile($file);
                }

            }

            closedir($dir);

        }
    }

}

?>
