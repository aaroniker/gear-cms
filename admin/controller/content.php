<?php

class contentController extends controller {

    public function __construct() {

    }

    public function index($action = '', $id = 0) {

        $this->model = new PageModel;

        if(ajax::is()) {

            $id = type::post('id', 'int', $id);
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

            } elseif($action == 'setHome') {

                if($id) {
                    if(option::set('home', $id)) {
                        message::success(lang::get('page_home_set'));
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

            } elseif($action == 'delete') {

                extension::add('model_beforeDelete', function($id) {
    			    if($id == option::get('home')) {
                        message::error(lang::get('page_delete_home'));
                        return false;
                    }
    		        return $id;
			    });

                extension::add('model_beforeDelete', function($id) {
                    $where = [
                        'meta_key' => 'parentID',
                        'meta_value' => $id
                    ];
                    if(db()->from('entry')->leftJoin('entry_meta ON id = entry_id')->where($where)->fetch()) {
                        message::error(lang::get('page_is_parent'));
                        return false;
                    }
                    return $id;
			    });

                if($this->model->delete($id)) {
                    message::success(lang::get('page_deleted'));
                }

            }

        }

        include(dir::view('content/list.php'));

    }

    public function menus($action = '', $id = 0) {

        $this->model = new MenuModel;

        if(ajax::is()) {

            $id = type::post('id', 'int', $id);

            if($action == 'get') {

                ajax::addReturn(json_encode(MenuItemModel::getAll($id)));

            } elseif($action == 'addItem') {

                $name = type::post('name', 'string', "");
                $pageID = type::post('pageID', 'int', 0);

                if($name) {
                    if($pageID) {
                        if($this->model->load($id)->addItem($name, 0, $pageID)) {
                            message::success(lang::get('menu_item_added'));
                        }
                    } else {
                        message::error(sprintf(lang::get('validate_required'), lang::get('page')));
                    }
                } else {
                    message::error(sprintf(lang::get('validate_required'), lang::get('name')));
                }

            } elseif($action == 'delItem') {
                if($id) {
                    if($this->model->load($id)->delete()) {
                        message::success(lang::get('menu_item_deleted'));
                    }
                }
            } elseif($action == 'add') {

                $name = type::post('name', 'string', '');

                if($name) {

                    $insert = $this->model->insert([
                        'name'=> $name
                    ]);

                    message::success(lang::get('menu_added'));

                    ajax::addReturn(json_encode($insert));

                } else {
                    message::error(sprintf(lang::get('validate_required'), lang::get('name')));
                }

            } elseif($action == 'edit') {

                //edit

            } elseif($action == 'move') {

                $array = type::post('array', 'array', []);

                foreach($array as $key => $val) {

                    $id = str_replace('item_', '', $val['id']);

                    $itemModel = new MenuItemModel($id);

                    $parentID = ($val['parentId']) ? str_replace('item_', '', $val['parentId']) : 0;

                    $values = [
                        'parentID' => $parentID,
                        'order' => $val['order']
                    ];

                    $itemModel->save($values);

                }

                message::success(lang::get('menu_item_moved'));

            } else {
                ajax::addReturn(json_encode(MenuModel::getAllFromDb()));
            }

        }

        if($action == 'delete') {
            if($id) {
                if($this->model->load($id)->deleteAllItems()->delete()) {
                    message::success(lang::get('menu_deleted'));
                }
            }
        }

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

                    $path = dir::media($path.filter::file($name));

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
