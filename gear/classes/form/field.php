<?php

abstract class formField extends form {

    public $name;
    public $hide;

    public $attributes;

    public $fieldName;

    public function __construct($name, $attributes = []) {

        $this->name = $name;
        $this->attributes = $attributes;

    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setHide($hide) {
        $this->hide = $hide;
    }

    public function fieldName($name) {

        $this->fieldName = $name;

        return $this;

    }

    public function addAttribute($name, $value = '') {

        if($name == 'class') {
            $this->addClass($value);
            return $this;
        }

        $this->attributes[$name] = $value;

        return $this;

    }

    public function hasAttribute($name) {
        return isset($this->attributes[$name]);
    }

    public function getAttribute($name, $default = false) {

        if($this->hasAttribute($name)) {
            return $this->attributes[$name];
        }

        return $default;

    }

    public function delAttribute($name) {

        unset($this->attributes[$name]);

        return $this;

    }

    protected function convertAttr($attr = false) {

        if(!$attr) {
            $attr = $this->attributes;
        }

        return parent::convertAttribute($attr);

    }

    public function addClass($class) {

        $this->attributes['class'][] = $class;

        return $this;

    }

    public function getName() {
        return $this->name;
    }

    abstract public function get();

}

?>
