<?php

class formTextarea extends formField {

    public function get() {

        $this->addAttribute('name', $this->name);
        $this->addClass('form-field');

        $this->addAttribute('rows', 8);

        $this->addAttribute('v-model', 'props.data.'.$this->name);

        return '<textarea'.$this->convertAttr().'></textarea>';

    }

}

?>
