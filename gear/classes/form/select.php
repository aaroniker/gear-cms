<?php

class formSelect extends formField {

    var $currentOpt = 0;

    var $output = [];

    public function __construct($name, $value, $attributes = []) {

        parent::__construct($name, $value, $attributes);
        $this->setSelected();

    }

    public function setSelected() {

        if(!is_array($this->value)) {
            $this->value = explode('|', $this->value);
        }

        $this->value = array_flip($this->value);

        return $this;

    }

    public function setSize($size) {

        $this->addAttribute('size', $size);

        return $this;

    }

    public function setMultiple($multiple = true) {

        if($multiple) {
            $this->addAttribute('multiple', 'multiple');
        } else {
            $this->delAttribute('multiple');
        }

        return $this;

    }

    public function addGroup($name, $attributes = []) {

        $attributes['label'] = $name;

        $this->currentOpt++;
        $this->output[$this->currentOpt] = ['attr'=>$attributes, 'option'=>[]];

        return $this;

    }

    public function add($name, $value, $attributes = []) {

        $attributes['value'] = $name;

        if(isset($this->value[$attributes['value']])) {
            $attributes['selected'] = 'selected';
        }

        $this->output[$this->currentOpt]['option'][] = ['name'=>$value, 'attr'=>$attributes];

        return $this;

    }

    protected function getOptions($options) {

        if(!is_array($options)) {
            //new Exception();
        }

        $return = '';

        foreach($options as $option) {
            $return .= '<option '.$this->convertAttr($option['attr']).'>'.$option['name'].'</option>'.PHP_EOL;
        }

        return $return;

    }

    public function get() {

        $this->addClass('form-field');

        $attributes = $this->attributes;
        $attributes['name'] = $this->name;

        $haveGroups = ($this->currentOpt !== 0);

        if($this->hasAttribute('multiple')) {
            $attributes['name'] .= '[]';
        }

        $return = '<select '.$this->convertAttr($attributes).'>'.PHP_EOL;

        foreach($this->output as $group) {

            if($haveGroups) {
                $return .= '<optgroup '.$this->convertAttr($group['attr']).'>'.PHP_EOL;
            }

            $return .= $this->getOptions($group['option']);

            if($haveGroups) {
                $return .= '</optgroup>'.PHP_EOL;
            }

        }

        $return .= '</select>'.PHP_EOL;

        return $return;

    }

}

?>
