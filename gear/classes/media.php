<?php

class media {

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

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = ($size > 0) ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];

    }

    public static function getAll($path) {

        $dirs = [];
        $files = [];

        $array = array_diff(scandir(dir::media($path)), array('.', '..'));

        foreach($array as $name) {

            $file = $path.$name;

            if(is_dir(dir::media($file))) {
                $count = count(scandir(dir::media($file))) - 2;
                $str = ($count == 1) ? lang::get('file') : lang::get('files');
                $dirs[] = [
                    'id' => str_replace(dir::media(), '', $file."/"),
                    'name' => $name,
                    'path' => str_replace(dir::media(), '', $file."/"),
                    'size' => $count.' '.$str,
                    'type' => 'dir'
                ];
            } else {
                $files[] = [
                    'id' => str_replace(dir::media(), '', $file."/"),
                    'name' => $name,
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

                if(!(new \FilesystemIterator($path))->valid()) {

                    rmdir($path);
                    message::success(lang::get('dir_deleted'));

                } else {
                    message::error(lang::get('dir_not_empty'));
                }

            } else {

                unlink($path);
                message::success(lang::get('file_deleted'));

            }

        } else {
            message::error(lang::get('dir_file_not_found'));
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

}

?>
