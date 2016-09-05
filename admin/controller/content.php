<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index() {

    }

    public function media($action = '', $file = '') {

        if(ajax::is()) {

            $path = type::post('path', 'string', '');

            if($action == 'get') {

                ajax::addReturn(json_encode(media::getAll($path)));

            } elseif($action == 'addDir') {

                $name = type::post('name', 'string', '');

                $path = dir::media($path.$name);

                if($name) {

                    if(!file_exists($path)) {
                        mkdir($path, 0777, true);
                        message::success(lang::get('dir_added'));
                    } else {
                        message::error(lang::get('dir_exists'));
                    }

                } else {

                    message::error(sprintf(lang::get('validate_required'), lang::get('name')));

                }

            } elseif($action == 'delete') {

                if($file) {
                    media::delete($file);
                }

            } elseif($action == 'upload') {

                $file = type::files('file');
                
                $name = $file['name'];
                $tmp = $file['tmp_name'];

                move_uploaded_file($tmp, dir::media($path.$name));

            }

        }

        include(dir::view('content/media/list.php'));

    }

}

?>
