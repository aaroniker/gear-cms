<?php

class formMedia extends formField {

	public function get() {

        $this->addAttribute('type', 'hidden');
        $this->addAttribute('name', $this->name);

        $this->addAttribute('v-model', 'fileName == false ? \''.type::super($this->name, '', $this->value).'\' : fileName');

        $ext = $this->getAttribute('ext');
        $ext = ($ext) ? explode(',', $ext) : [];
        $this->delAttribute('ext');

		return '
            <div class="formMedia">
                <a @click="addMediaModal = true" class="button border">
                    <i class="icon icon-archive"></i>
                    '.lang::get('choose').'
                </a>
                <a class="button none sm" v-text="fileName == false ? \''.$this->value.'\' : fileName">'.$this->value.'</a>
                <input '.$this->convertAttr().'>
                <modal v-if="addMediaModal" @close="addMediaModal = false">
                    <h3 slot="header">'.lang::get('choose').'</h3>
                    <div slot="content">
                        <file-table :headline="headline" :search="search" table-type="select" ext=\''.json_encode($ext).'\'></file-table>
                    </div>
                </modal>
            </div>
        ';

	}

}

?>
