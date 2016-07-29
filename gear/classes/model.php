<?php

class model {

    protected $id;
    protected $model;
    protected $metaData = [];

    function __construct() {
        return $this;
    }

    public function __get($var) {

        if(property_exists(get_class($this), $var))
            return $this->$var;
        elseif(array_key_exists($var, $this->metaData))
            return $this->getMeta($var);

    }

    public static function getAllList() {

        $return = [];

        $fromDb = self::getAllFromDb();

        foreach($fromDb as $Db) {
            $class = get_called_class();
            $items[] = new $class($Db->id);
        }

        return $return;

    }

    public static function getAllFromDb() {

        $class = get_called_class();
        $class = new $class;

        return db()->from($class->model)->fetchAll();

    }

    protected function getClassVars() {
        return array_keys(get_class_vars(get_class($this)));
    }

    private function _getDBVars() {

        $array = [];

        foreach($this->getClassVars() as $var) {
            if($var != 'model' && $var != 'metaData') {
                $array[$var] = $this->$var;
            }
        }

        unset($array["id"]);
        return $array;
    }

    public function load($id = 0) {

        if($id > 0) {

            $data = $this->getById($id);

            foreach(get_class_vars(get_class($this)) as $cvar => $val) {
                if(isset($data->$cvar)) {
                    $this->$cvar = $data->$cvar;
                }
            }

            $this->loadMeta();

        }

        return $this;

    }

    public function getById($id = 0) {

        if($id > 0) {
            return db()->from($this->model)->where('id', $id)->fetch();
        }

    }

    public function count() {
        return db()->from($this->model)->count();
    }

    public function insert($args) {

        $save = [];
        $meta = [];

        foreach($args as $key => $value) {

            $value = (!isset($value)) ? '' : $value;

            if(!in_array($key, $this->metaData)) {
                $save[$key] = $value;
            } else {
                $meta[$key] = $value;
            }

        }

        $save = extension::get('model_beforeInsert', $save);

        if(is_array($save)) {

            $insertId = db()->insertInto($this->model, $save)->execute();

            $this->setData($meta)->saveMeta($insertId);

            if((int)$insertId > 0) {
                return $this->load($insertId);
            }

        }

    }

    private function setData($data) {

        $this->metaData = [];

        foreach($data as $key => $value) {

            if(property_exists(get_class($this), $key)) {
                $this->$key = $value;
            } else {
                $this->metaData[$key] = $value;
            }

        }

        return $this;

    }

    public function save($data) {

        if(is_array($data)) {

            $this->setData($data);

            $metaDataEdited = $this->saveMeta();
            $dataEdited = $this->saveData();

            return $metaDataEdited || $dataEdited ? true : false;

        }

    }

    private function getMeta($meta_key) {

        if($meta_key) {
            return $this->metaData[$meta_key];
        }

    }

    private function loadMeta() {

        $data = db()->from($this->model.'_meta')->where($this->model.'_id', $this->id)->fetchPairs('meta_key', 'meta_value');

        return array_merge($this->metaData, $data);

    }

    private function saveData() {
        return db()->update($this->model)->set($this->_getDBVars())->where('id', $this->id)->execute();
    }

    private function saveMeta($insert = false) {

        $edited = [];

        $this->id = ($this->id) ? $this->id : $insert;

        foreach($this->metaData as $meta_key => $meta_value) {

            if(!isset($meta_value)) {

                $whereMeta = [
                    $this->model.'_id' => $this->id,
                    'meta_key' => $meta_key
                ];

                db()->deleteFrom($this->model.'_meta')->where($whereMeta)->execute();

            } else {

                $whereMeta = [
                    $this->model.'_id' => $this->id,
                    'meta_key' => $meta_key
                ];

                $data = db()->from($this->model.'_meta')->where($whereMeta)->fetchAll();

                if(!count($data) || $insert) {

                    $insert = (!count($data)) ? $this->id : $insert;

                    $args = [
                        'meta_key' => $meta_key,
                        'meta_value' => $meta_value,
                        $this->model.'_id' => $insert
                    ];

                    $edited[] = db()->insertInto($this->model.'_meta')->values($args)->execute();

                } else {

                    $args = [
                        'meta_key' => $meta_key,
                        $this->model.'_id' => $this->id
                    ];

                    $edited[] = db()->update($this->model.'_meta')->set('meta_value', $meta_value)->where($args)->execute();

                }

            }

        }

        return in_array(1, $edited) ? true : false;

    }

    public function delete() {

        if($this->id) {

            db()->deleteFrom($this->model.'_meta')->where($this->model.'_id', $this->id)->execute();
            db()->deleteFrom($this->model)->where('id', $this->id)->execute();

            return true;

        } else {
            return false;
        }

    }

}

?>
