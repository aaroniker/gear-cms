<?php

class formMedia extends formField {

	public function get() {

        $ext = $this->getAttribute('ext');
        $ext = ($ext) ? explode(',', $ext) : [];

        return '<form-media value="'.$this->value.'" name="'.$this->name.'" value2="'.type::super($this->name, '', $this->value).'" ext=\''.json_encode($ext).'\'></form-media>';

	}

}

?>
