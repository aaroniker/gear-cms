<?php

class formSelect extends formField {

    public $output = [];

    public function __construct($name, $attributes = []) {

        parent::__construct($name, $attributes);

    }

    public function add($key, $value) {

        $this->output[(string)$key] = $value;

        return $this;

    }

    public function get() {

        $attributes = $this->attributes;

        $attributes['v-model'] = 'props.data.'.$this->name;

        return "<g-select ".$this->convertAttr($attributes)." :list='".json_encode($this->output, JSON_FORCE_OBJECT)."'></g-select>";

    }

}

?>
