<?php

class formMedia extends formField {

	public function get() {

        $this->addAttribute('type', 'hidden');
        $this->addAttribute('name', $this->name);
        $this->addAttribute('value', type::super($this->name, '', '{{ fileName ? fileName : "'.$this->value.'" }}'));

		return '
            <div class="formMedia">
                <a @click="addMediaModal = true" class="button border">
                    <i class="icon icon-archive"></i>
                    '.lang::get('choose').'
                </a>
                <a class="button none">{{ fileName ? fileName : "'.$this->value.'" }}</a>
                <input'.$this->convertAttr().'>
                <modal :show.sync="addMediaModal">
                    <h3 slot="header">'.lang::get('choose').'</h3>
                    <div slot="content">
                        <file-table :data=\''.json_encode(media::getAll('/')).'\' :headline="headline" :select="true" :filter-key="search"></file-table>
                    </div>
                </modal>
            </div>
        ';

	}

}

?>
