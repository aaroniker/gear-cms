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

        } elseif($action == 'delete') {

            if($id) {

                if($id == user::current()->id) {

                    echo message::error(lang::get('user_delete_own'));

                } else {

                    $this->model->load($id)->delete();

                    echo message::success(lang::get('user_delete'));

                }

            }

        } else {

            include(dir::view('user/list.php'));

        }

    }

    public function permissions($action = '', $id = 0) {

        $this->model = new PermissionModel;

        if(ajax::is()) {

            $id = type::post('id', 'int', 0);

            if($action == 'get') {

                $this->model->load($id);

                $perms = ($this->model->permissions) ? unserialize($this->model->permissions) : [];

                ajax::addReturn(json_encode($perms));

            } elseif($action == 'add') {

                $name = type::post('name', 'string', '');

                $this->model->insert([
                    'name'=> $name
                ]);

            } elseif($action == 'edit') {

                $perms = type::post('perms');
                $perms = ($perms) ? serialize($perms) : null;

                $this->model->load($id);

                $save = [
                    'permissions' => $perms
                ];

                $this->model->save($save);

            } else {

                ajax::addReturn(json_encode(PermissionModel::getAllFromDb()));

            }

        }

        if($action == 'delete') {
            if($id) {

                $this->model->load($id)->delete();

                echo message::success(lang::get('permission_group_delete'));

            }
        }

        include(dir::view('user/permissions/list.php'));

    }

}

?>
