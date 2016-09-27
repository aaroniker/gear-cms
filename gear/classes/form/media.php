<?php

class formMedia extends formField {

	public function get() {

        $this->addAttribute('type', 'hidden');
        $this->addAttribute('name', $this->name);
        $this->addAttribute('value', type::super($this->name, '', $this->value));

		return '
            <div>'.type::super($this->name, '', $this->value).'</div>
            <input'.$this->convertAttr().'>
        ';

	}

}

?>
