<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index() {

    }

    public function media($action = '', $file = '') {

        if(ajax::is()) {

            $path = type::post('path', 'string', '');
            $name = type::post('name', 'string', '');
            $file = type::post('file', 'string', $file);

            if($action == 'get') {

                ajax::addReturn(json_encode(media::getAll($path)));

            } elseif($action == 'addDir') {

                $name = filter::url($name);

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

            } elseif($action == 'move') {

                if(media::move($file, $path.$name)) {
                    message::success(lang::get('file_moved'));
                } else {
                    message::error(lang::get('file_not_moved'));
                }

            } elseif($action == 'edit') {

                $name = filter::file($name);

                if($name) {

                    if(media::move($file, $path.$name)) {
                        message::success(lang::get('file_edited'));
                    } else {
                        message::error(lang::get('file_not_edited'));
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

                media::upload($file);

            }

        }

        include(dir::view('content/media/list.php'));

    }

}

?>
