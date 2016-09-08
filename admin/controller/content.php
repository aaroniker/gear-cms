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

                if($name) {

                    $path = dir::media($path.$name);

                    media::addDir($path);

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

                media::upload(type::files('file'));

            }

        }

        include(dir::view('content/media/list.php'));

    }

}

?>
