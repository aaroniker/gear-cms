<?php

class formInput extends formField {

    public function get() {

        $this->addAttribute('name', $this->name);

        $this->addClass('form-field');

        $this->addAttribute('v-model', 'props.data.'.$this->name);

        return '<input'.$this->convertAttr().'>';

    }

}

?>
