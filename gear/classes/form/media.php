<?php

class formMedia extends formField {

	public function get() {

        $this->addAttribute('type', 'hidden');
        $this->addAttribute('name', $this->name);
        $this->addAttribute('value', type::super($this->name, '', $this->value));

		return '
            <div class="formMedia">
                <a @click="addMediaModal = true" class="button border">'.lang::get('choose').'</a>
                <input'.$this->convertAttr().'>
                <modal :show.sync="addMediaModal">
                    <h3 slot="header">'.lang::get('choose').'</h3>
                    <div slot="content">
                        123
                    </div>
                </modal>
            </div>
        ';

	}

}

?>
