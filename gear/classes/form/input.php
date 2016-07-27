<?php

class formInput extends formField {

    public function get() {

        $this->addAttribute('name', $this->name);
        $this->addAttribute('value', type::super($this->name, '', $this->value));

        $this->addClass('form-field');

        return '<input'.$this->convertAttr().'>';

    }

}

?>
