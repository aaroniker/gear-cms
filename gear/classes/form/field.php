<?php

abstract class formField extends form {

    var $name;
    var $value;
    var $hide;

    var $attributes;

    var $fieldName;

    public function __construct($name, $value, $attributes = []) {

        $this->name = $name;
        $this->value = $value;

        $this->attributes = $attributes;

    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setHide($hide) {
        $this->hide = $hide;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function fieldName($name) {

        $this->fieldName = $name;

        return $this;

    }

    public function fieldValidate($rule = 'required') {

        validate::set_field_name($this->name, $this->fieldName);

        self::addRule($this->name, $rule);

        if(strpos($rule, 'required') !== false) {
            $this->fieldName($this->fieldName.' *');
        }

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

        return html_convertAttribute($attr);

    }

    public function addClass($class) {

        $this->attributes['class'][] = $class;

        return $this;

    }

    public function getName() {
        return $this->name;
    }

    public function getLabel() {
        return filter::url($this->name).'Form';
    }

    public function getHide() {
        return ($this->hide) ? 'hide' : '';
    }

    abstract public function get();

}

?>
