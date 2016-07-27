<?php

class formTextarea extends formField {

	public function get() {
		
		$this->addAttribute('name', $this->name);
		$this->addClass('form-field');

		$this->addAttribute('rows', 8);

		return '<textarea'.$this->convertAttr().'>'.htmlspecialchars(type::super($this->name, '', $this->value)).'</textarea>';

	}

}

?>
