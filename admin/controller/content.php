<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index($action = '', $id = 0) {

        $this->model = new PageModel;

        if(ajax::is()) {

            $id = type::post('id', 'int', 0);
            $parentID = type::post('parent', 'int', 0);

            if($action == 'get') {

                $return = [
                    'tree' => PageModel::getAll(),
                    'all' => PageModel::getAllFromDb()
                ];

                ajax::addReturn(json_encode($return));

            } elseif($action == 'move') {

                if($id != $parentID) {

                    $this->model->load($id);

                    $save = [
                        'parentID' => $parentID
                    ];

                    if($this->model->save($save)) {
                        message::success(lang::get('page_moved'));
                    }

                }

            } elseif($action == 'add') {

                $name = type::post('name', 'string', '');

                if($name) {

                    $this->model->insert([
                        'name'=> $name,
                        'parentID' => $parentID,
                        'siteURL' => filter::url($name)
                    ]);

                    message::success(lang::get('page_added'));

                } else {
                    message::error(sprintf(lang::get('validate_required'), lang::get('name')));
                }

            }

        }

        include(dir::view('content/list.php'));

    }

    public function menus() {

        include(dir::view('content/menus/list.php'));

    }

    public function media($action = '', $file = '') {

        if(ajax::is()) {

            $path = type::post('path', 'string', '');
            $name = type::post('name', 'string', '');
            $file = type::post('file', 'string', $file);

            if($action == 'get') {

                ajax::addReturn(json_encode(media::getAll($path)));

            } elseif($action == 'addDir') {

                if($name) {

                    $path = dir::media($path.filter::url($name));

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

                if($name) {

                    if(media::move($file, $path.filter::file($name))) {
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
