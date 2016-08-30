<?php

class userController extends controller {

    public function __construct() {
        $this->model = new UserModel;
    }

    public function index($action = '', $id = 0) {

        if($action == 'add') {

            include(dir::view('user/add.php'));

        } elseif($action == 'edit') {

            $id = ($id) ? $id : user::current()->id;

            $this->model->load($id);

            include(dir::view('user/edit.php'));

        } else {

            if($action == 'delete') {

                extension::add('model_beforeDelete', function($id) {
    			    if((is_array($id) && in_array(user::current()->id, $id)) || $id == user::current()->id) {
                        message::error(lang::get('user_delete_own'));
                        return false;
                    }
    		        return $id;
			    });

                if($this->model->delete($id)) {
                    message::success(lang::get('user_delete'));
                }

            }

            include(dir::view('user/list.php'));

        }

    }

    public function permissions($action = '', $id = 0) {

        $this->model = new PermissionModel;

        if(ajax::is()) {

            $id = type::post('id', 'int', 0);

            if($action == 'get') {

                if($id) {

                    $this->model->load($id);

                    $perms = ($this->model->permissions) ? unserialize($this->model->permissions) : [];

                } else {
                    $perms = userPerm::getAll();
                }

                ajax::addReturn(json_encode($perms));

            } elseif($action == 'add') {

                $name = type::post('name', 'string', '');

                if($name) {

                    $this->model->insert([
                        'name'=> $name
                    ]);

                    message::success(lang::get('permission_group_added'));

                } else {

                    message::error(sprintf(lang::get('validate_required'), lang::get('name')));

                }

            } elseif($action == 'edit') {

                $perms = type::post('perms');
                $perms = ($perms) ? serialize($perms) : null;

                $this->model->load($id);

                $save = [
                    'permissions' => $perms
                ];

                if($this->model->save($save)) {
                    message::success(lang::get('permission_saved'));
                }

            } else {

                $return[] = [
                    'id' => 0,
                    'name' => lang::get('admin')
                ];

                ajax::addReturn(json_encode(array_merge(PermissionModel::getAllFromDb(), $return)));

            }

        }

        if($action == 'delete') {
            if($id) {

                $this->model->load($id)->delete();

                message::success(lang::get('permission_group_delete'));

            }
        }

        include(dir::view('user/permissions/list.php'));

    }

}

?>
