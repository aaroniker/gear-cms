<?php

class UserModel extends model {

    protected $id;
    protected $password;
    protected $email;
    protected $status;

    public function __construct($id = 0) {

        $this->model = 'user';

        $this->log = 'user';

        $this->metaData = [
            "username",
            "avatar",
            "groupID",
            "openMenu",
            "smallMenu"
        ];

        if($id) {
            $this->load($id);
        }

        if($this->groupID == 0) {

            $this->admin = true;

        } else {

            $permGroup = new PermissionModel($this->groupID);

            $this->permissions = unserialize($permGroup->permissions);

        }

        return $this;

    }

    public static function getAll() {

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {
                $user = new UserModel($val->id);
                if($user->groupID) {
                    $permission = new PermissionModel($user->groupID);
                    $getAllFromDb[$key]->permissionGroup = $permission->name;
                } else {
                    $getAllFromDb[$key]->permissionGroup = lang::get('admin');
                }
            }
        }

        return $getAllFromDb;
    }

}

?>
