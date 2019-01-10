<?php

class form {

    protected $method;
    protected $action;

    protected $values = ['trigger' => true];

    protected $props = [];

    protected $horizontal = false;

    protected $rows = [];

    public function __construct($action = '', $method = 'POST') {

        $this->method = $method;
        $this->action = $action;

        $this->addProp('action', $this->action);
        $this->addProp('method', $this->method);

        $this->addRow();

    }

    private function addElement($name, $object) {

        $this->rows[count($this->rows)][$name] = $object;

        return $object;

    }

    private function addField($name, $value, $class, $attributes = []) {

        $field = new $class($name, $attributes);

        $this->addElement($name, $field);

        if($value) {
            $this->values[$name] = $value;
        }

        return $field;

    }

    public function addRaw($text, $value = null, $attributes = []) {
        return $this->addField($text, $value, 'formRaw', $attributes);
    }

    public function addText($name, $value = null, $attributes = []) {

        $attributes['type'] = 'text';

        return $this->addField($name, $value, 'formInput', $attributes);

    }

    public function addPassword($name, $value = null, $attributes = []) {

        $attributes['type'] = 'password';

        return $this->addField($name, $value, 'formInput', $attributes);

    }

    public function addRadio($name, $value = null, $attributes = []) {
        return $this->addField($name, $value, 'formRadio', $attributes);
    }

    public function addRadioInline($name, $value = null, $attributes = []) {

        $attributes['inline'] = true;

        return $this->addField($name, $value, 'formRadio', $attributes);

    }

    public function addCheckbox($name, $value = null, $attributes = []) {
        return $this->addField($name, $value, 'formCheckbox', $attributes);
    }

    public function addCheckboxInline($name, $value = null, $attributes = []) {

        $attributes['inline'] = true;

        return $this->addField($name, $value, 'formCheckbox', $attributes);

    }

    public function addSwitch($name, $value = null, $attributes = []) {

        $attributes['switch'] = true;

        return $this->addField($name, $value, 'formCheckbox', $attributes);

    }

    public function addSwitchInline($name, $value = null, $attributes = []) {

        $attributes['switch'] = true;
        $attributes['inline'] = true;

        return $this->addField($name, $value, 'formCheckbox', $attributes);

    }

    public function addHidden($name, $value = null, $attributes = []) {

        $attributes['type'] = 'hidden';

        return $this->addField($name, $value, 'formInput', $attributes);

    }

    public function addSelect($name, $value = null, $attributes = []) {
        return $this->addField($name, $value, 'formSelect', $attributes);
    }

    public function addTextarea($name, $value = null, $attributes = []) {
        return $this->addField($name, $value, 'formTextarea', $attributes);
    }

    public function setHorizontal($bool) {
        $this->horizontal = $bool;
    }

    public function addRow() {

        $count = count($this->rows) + 1;

        $this->rows[$count] = [];

        return $this;

    }

    public static function convertAttribute($attributes) {

        $return = '';

        foreach($attributes as $key => $val) {
            if(is_int($key)) {
                $return .= ' '.$val;
            } else {
                $val = (is_array($val)) ? implode(' ', $val) : $val;
                $return .= ' '.htmlspecialchars($key).'="'.htmlspecialchars($val).'"';
            }
        }

        return $return;

    }

    public function set($values) {
        if(isset($values) && is_array($values)) {
            $this->values = array_merge($this->values, $values);
        }
    }

    public function addProp($name, $value) {
        $this->props[$name] = (isset($this->props[$name])) ? $this->props[$name].' '.$value : $value;
    }

    private function loopFields($data, $return) {

        foreach($data as $show) {
            if($show->getAttribute('type') == 'hidden') {
                $return[] = $show->get();
                continue;
            }
            $col = ($show->getAttribute('col')) ? $show->getAttribute('col') : 'xs-12';
            $return[] = '<div class="'.$col.'">';
            $return[] = '<div class="form-element">';
            if($show->fieldName) {
                $label = __($show->fieldName);
                $return[] = ($this->horizontal) ? '<label class="sm-3">'.$label.'</label>' : '<label>'.$label.'</label>';
                $return[] = ($this->horizontal) ? '<div class="sm-9">'.$show->get().'</div>' : $show->get();
            } else {
                $return[] = ($this->horizontal) ? '<div class="sm-12">'.$show->get().'</div>' : $show->get();
            }
            $return[] = '</div>';
            $return[] = '</div>';
        }

        return $return;

    }

    public function show() {

        if($this->horizontal) {
            $this->addProp('class', 'horizontal');
        }

        $return = [];
        $return[] = "<g-form :values='".json_encode($this->values)."' ".self::convertAttribute($this->props).">".PHP_EOL;
        $return[] = '<template slot-scope="props">';

        foreach($this->rows as $content) {
            $return[] = '<div class="columns">';
            $return = $this->loopFields($content, $return);
            $return[] = '</div>';
        }

        $return[] = '</template>';
        $return[] = '</g-form>'.PHP_EOL;

        return implode(PHP_EOL, $return);

    }

}

?>
