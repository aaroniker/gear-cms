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
            "session_ids",
            "permissionID"
        ];

        if($id) {
            $this->load($id);
        }

        if($this->permissionID == 0) {

            $this->admin = true;

        } else {

            $permGroup = new PermissionModel($this->permissionID);

            $this->permissions = unserialize($permGroup->permissions);

        }

        return $this;

    }

    public static function getAll() {

        $getAllFromDb = self::getAllFromDb();

        if(is_array($getAllFromDb)) {
            foreach($getAllFromDb as $key => $val) {
                $user = new UserModel($val->id);
                if($user->permissionID) {
                    $permission = new PermissionModel($user->permissionID);
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
