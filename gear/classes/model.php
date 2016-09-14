<?php

class model {

    protected $id;
    protected $model;
    protected $type = false;
    protected $metaData = [];

    function __construct() {
        return $this;
    }

    public function __get($var) {

        if(property_exists(get_class($this), $var)) {
            return $this->$var;
        } elseif(array_key_exists($var, $this->metaData)) {
            return $this->getMeta($var);
        }

    }

    public static function getAllFromDb() {

        $class = get_called_class();
        $class = new $class;

        if($class->type) {
            return db()->from($class->model)->where('type', $class->type)->fetchAll();
        } else {
            return db()->from($class->model)->fetchAll();
        }

    }

    protected function getClassVariables() {
        return array_keys(get_class_vars(get_class($this)));
    }

    protected function getDBVariables() {

        $array = [];

        foreach($this->getClassVariables() as $var) {
            if($var != 'model' && $var != 'metaData' && $var != 'type') {
                $array[$var] = $this->$var;
            }
        }

        unset($array["id"]);

        return $array;
    }

    public function load($id = 0) {

        $data = $this->getById($id);

        foreach(get_class_vars(get_class($this)) as $cvar => $val) {
            if(isset($data->$cvar)) {
                $this->$cvar = $data->$cvar;
            }
        }

        $this->metaData = $this->loadMeta();

        return $this;

    }

    public function getById($id = 0) {

        if($id > 0) {
            return db()->from($this->model)->where('id', $id)->fetch();
        }

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

        if($this->type) {
            $save['type'] = $this->type;
        }

        $save = extension::get('model_beforeInsert', $save);

        if(is_array($save)) {

            $this->id = db()->insertInto($this->model, $save)->execute();

            $this->setData($meta)->saveMeta();

            return $this->load($this->id);

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

            $data = extension::get('model_beforeSave', $data);

            $this->setData($data);

            $saveData = $this->saveData();
            $saveMeta = $this->saveMeta();

            return ($saveData || $saveMeta) ? true : false;

        }

        return false;

    }

    private function getMeta($key) {
        return ($key) ? $this->metaData[$key] : false;
    }

    private function loadMeta() {

        $data = db()->from($this->model.'_meta')->where($this->model.'_id', $this->id)->fetchPairs('meta_key', 'meta_value');

        return array_merge($this->metaData, $data);

    }

    private function saveData() {
        return db()->update($this->model)->set($this->getDBVariables())->where('id', $this->id)->execute();
    }

    private function saveMeta() {

        $return = false;

        foreach($this->metaData as $meta_key => $meta_value) {

            if(is_null($meta_value)) {

                $where = [
                    $this->model.'_id' => $this->id,
                    'meta_key' => $meta_key
                ];

                $return = db()->deleteFrom($this->model.'_meta')->where($where)->execute();

            } else {

                $where = [
                    $this->model.'_id' => $this->id,
                    'meta_key' => $meta_key
                ];

                $data = db()->from($this->model.'_meta')->where($where)->fetchAll();

                if(!count($data)) {

                    $values = [
                        'meta_key' => $meta_key,
                        'meta_value' => $meta_value,
                        $this->model.'_id' => $this->id
                    ];

                    $return = db()->insertInto($this->model.'_meta')->values($values)->execute();

                } else {

                    $where = [
                        'meta_key' => $meta_key,
                        $this->model.'_id' => $this->id
                    ];

                    $return = db()->update($this->model.'_meta')->set('meta_value', $meta_value)->where($where)->execute();

                }

            }

        }

        return $return;

    }

    public function count() {
        if($this->type) {
            return db()->from($this->model)->where('type', $this->type)->count();
        } else {
            return db()->from($this->model)->count();
        }
    }

    public function delete($id = 0) {

        $id = ($id) ? $id : $this->id;

        if(strpos($id, ',') !== false) {

            $ids = explode(',', $id);

            $ids = extension::get('model_beforeDelete', $ids);

            if($ids) {

                foreach($ids as $id) {

                    db()->deleteFrom($this->model.'_meta')->where($this->model.'_id', $id)->execute();
                    db()->deleteFrom($this->model)->where('id', $id)->execute();

                }

                return true;

            }

        } else {

            if(extension::get('model_beforeDelete', $id)) {

                db()->deleteFrom($this->model.'_meta')->where($this->model.'_id', $id)->execute();
                db()->deleteFrom($this->model)->where('id', $id)->execute();

                return true;

            }

        }

        return false;

    }

}

?>
