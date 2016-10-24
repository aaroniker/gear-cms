<?php

class formMedia extends formField {

	public function get() {

        $this->addAttribute('type', 'hidden');
        $this->addAttribute('name', $this->name);
        $this->addAttribute('value', type::super($this->name, '', '{{ (fileName === false) ? "'.$this->value.'" : fileName }}'));

        $ext = $this->getAttribute('ext');
        $ext = ($ext) ? explode(',', $ext) : [];
        $this->delAttribute('ext');

		return '
            <div class="formMedia">
                <a @click="addMediaModal = true" class="button border">
                    <i class="icon icon-archive"></i>
                    '.lang::get('choose').'
                </a>
                <a class="button none sm">{{ (fileName === false) ? "'.$this->value.'" : fileName }}</a>
                <input'.$this->convertAttr().'>
                <modal v-if="addMediaModal" @close="addMediaModal = false">
                    <h3 slot="header">'.lang::get('choose').'</h3>
                    <div slot="content">
                        <file-table :data=\''.json_encode(media::getAll('/')).'\' :headline="headline" :select="true" :ext=\''.json_encode($ext).'\' :filter-key="search"></file-table>
                    </div>
                </modal>
            </div>
        ';

	}

}

?>
