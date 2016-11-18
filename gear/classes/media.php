<?php

class media {

    static $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    public static function getUniqueName($path, $filename) {

        if($pos = strrpos($filename, '.')) {
            $name = substr($filename, 0, $pos);
            $ext = substr($filename, $pos);
        } else {
            $name = $filename;
        }

        $newpath = $path.'/'.$filename;
        $newname = $filename;
        $counter = 0;

        while (file_exists($newpath)) {
            $newname = $name .'_'. $counter . $ext;
            $newpath = $path.'/'.$newname;
            $counter++;
        }

        return $newname;

    }

    public static function size($path) {

        $size = filesize($path);

        $power = ($size > 0) ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), 0, '.', ',') . ' ' . self::$units[$power];

    }

    public static function getAll($path) {

        $dirs = [];
        $files = [];

        $array = array_diff(scandir(dir::media($path)), ['.', '..']);

        if($path && $path != '/') {
            $up = substr($path, 0, strrpos($path, '/'));
            $dirs[] = [
                'id' => '',
                'name' => '..',
                'path' => substr($up, 0, strrpos($up, '/')).'/',
                'size' => '',
                'type' => 'dir'
            ];
        }

        foreach($array as $name) {

            $file = $path.$name;

            if(is_dir(dir::media($file))) {
                $count = count(scandir(dir::media($file))) - 2;
                $str = ($count == 1) ? lang::get('file') : lang::get('files');
                $dirs[] = [
                    'id' => str_replace(dir::media().'/', '', $file),
                    'name' => $name,
                    'path' => str_replace(dir::media().'/', '', $file.'/'),
                    'size' => $count.' '.$str,
                    'type' => 'dir'
                ];
            } else {
                $files[] = [
                    'id' => str_replace(dir::media().'/', '', $file),
                    'name' => $name,
                    'url' => url::media($file),
                    'size' => self::size(dir::media($file)),
                    'type' => 'file'
                ];
            }

        }

        return array_merge($dirs, $files);

    }

    private static function deleteSingle($path) {

        if(file_exists($path)) {

            if(is_dir($path)) {

                $count = array_diff(scandir($path), ['.', '..']);

                if(!count($count)) {

                    if(rmdir($path)) {
                        message::success(lang::get('dir_deleted'));
                    } else {
                        message::error(lang::get('error_unknown'));
                    }

                } else {
                    message::error(lang::get('dir_not_empty'));
                }

            } else {

                if(unlink($path)) {
                    message::success(lang::get('file_deleted'));
                } else {
                    message::error(lang::get('error_unknown'));
                }

            }

        } else {
            message::error(lang::get('dir_file_not_found'));
        }

    }

    public static function move($file, $destination) {

        if(file_exists(dir::media($destination))) {
            return false;
        }

        return rename(dir::media($file), dir::media($destination));

    }

    public static function upload($file, $path) {

        $name = self::getUniqueName(dir::media($path), $file['name']);

        return move_uploaded_file($file['tmp_name'], dir::media($path.$name));

    }

    public static function addDir($path) {

        if(!file_exists($path)) {

            if(mkdir($path, 0777, true)) {
                message::success(lang::get('dir_added'));
            } else {
                message::error(lang::get('dir_not_added'));
            }

        } else {
            message::error(lang::get('dir_exists'));
        }

    }

    public static function delete($file) {

        if(strpos($file, ',') !== false) {

            $files = explode(',', $file);

            if($files) {

                foreach($files as $file) {

                    $file = trim($file, '/');

                    self::deleteSingle(dir::media($file));

                }

            }

        } else {

            $file = trim($file, '/');

            self::deleteSingle(dir::media($file));

        }

    }

    public static function getServerMaxSize($bytes = true) {

        static $max_size = -1;

        if($max_size < 0) {
            $max_size = self::parseSize(ini_get('post_max_size'));

            $upload_max = self::parseSize(ini_get('upload_max_filesize'));

            if($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }

        if($bytes) {
            return $max_size;
        } else {

            $power = ($max_size > 0) ? floor(log($max_size, 1024)) : 0;

            return number_format($max_size / pow(1024, $power), 0, '.', ',') . ' ' . self::$units[$power];

        }

    }

    public static function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

}

?>
