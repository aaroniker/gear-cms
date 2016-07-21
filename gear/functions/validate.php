<?php

    function validate($data, $addRules = []) {

        $rules = [
            'email' => 'valid_email'
        ];

        $rulesArray = array_merge($rules, $addRules);

        validate::set_field_name("email", lang::get('email'));
        validate::set_field_name("password", lang::get('password'));

        $validate = new validate();

        $validate->validation_rules($rulesArray);

        $is_valid = ($validate->run($data) === false) ? false : true;

        return [$is_valid, $validate->get_readable_errors()];

    }

    function file_rename($path, $filename) {

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

    function file_size($path) {

        $size = filesize($path);

        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];

    }

?>
