<?php

class model {

    protected $id;
    protected $model;
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

        return db()->from($class->model)->fetchAll();

    }

    protected function getClassVariables() {
        return array_keys(get_class_vars(get_class($this)));
    }

    protected function getDBVariables() {

        $array = [];

        foreach($this->getClassVariables() as $var) {
            if($var != 'model' && $var != 'metaData') {
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

            $this->setData($data);

            $this->saveData();
            $this->saveMeta();

            return true;

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

        foreach($this->metaData as $meta_key => $meta_value) {

            if(!isset($meta_value)) {

                $where = [
                    $this->model.'_id' => $this->id,
                    'meta_key' => $meta_key
                ];

                db()->deleteFrom($this->model.'_meta')->where($where)->execute();

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

                    db()->insertInto($this->model.'_meta')->values($values)->execute();

                } else {

                    $where = [
                        'meta_key' => $meta_key,
                        $this->model.'_id' => $this->id
                    ];

                    db()->update($this->model.'_meta')->set('meta_value', $meta_value)->where($where)->execute();

                }

            }

        }

        return true;

    }

    public function count() {
        return db()->from($this->model)->count();
    }

    public function delete() {

        if($this->id) {

            db()->deleteFrom($this->model.'_meta')->where($this->model.'_id', $this->id)->execute();
            db()->deleteFrom($this->model)->where('id', $this->id)->execute();

            return true;

        }

        return false;

    }

}

?>
