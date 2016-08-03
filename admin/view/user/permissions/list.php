<section id="permissions">

    <header>

        <h2><?=lang::get('permissions'); ?></h2>

    </header>

    <?php

        $form = new form();
        $form->setHorizontal(false);

        $field = $form->addTextField('name', '');
        $field->fieldName(lang::get('name'));
        $field->fieldValidate();

        if($form->isSubmit()) {

            if($form->validation()) {

			    $this->model->insert($form->getAll());

    			echo message::success(lang::get('permission_group_added'));

		    } else {
			    echo $form->getErrors();
		    }

	    }

    ?>

    <modal :show.sync="showModal">
        <h3 slot="header"><?=lang::get('add'); ?></h3>
        <div slot="content">
            <?=$form->show(); ?>
        </div>
    </modal>

    <div class="columns">

        <div class="md-3">

            <nav class="tabs">
                <ul>
                    <li v-for="group in groups" :class="$index == index ? 'active' : ''">
                        <a href="#" @click.prevent="setActive($index, group.id)">
                            {{ group.name }}
                        </a>
                        <div>
                            <a href="">
                                <i class="icon icon-edit"></i>
                            </a>
                            <a href="<?=config::get('url').'admin/user/permissions/delete/'; ?>{{ group.id }}">
                                <i class="icon icon-trash-a"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <button class="button border" @click="showModal = true"><?=lang::get('add'); ?></button>

        </div>

        <div class="md-9">

            <div v-for="group in groups">
                <div v-if="index == $index">

                    <ul class="unstyled">
                        <li v-for="perm in perms">
                            <div class="checkbox">
                                <input id="{{ $key }}" type="checkbox" :value="$key" v-model="checked">
                                <label for="{{ $key }}"></label>
                                <div>{{ perm }}</div>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>

        </div>

    </div>

</section>

<?php
    theme::addJSCode('
        new Vue({
            el: "#permissions",
            data: {
                index: 0,
                groupid: 0,
                groups: [],
                perms: '.json_encode(userPerm::getAll()).',
                checked: [],
                showModal: false
            },
            ready: function() {
                this.fetchGroups();
            },
            watch: {
                checked: function() {
                    var vue = this;

                    $.ajax({
                        method: "POST",
                        url: url + "admin/user/permissions",
                        dataType: "json",
                        data: { method: "savePerm", id: vue.groupid, perms: vue.checked }
                    }).done(function(data) {

                    });
                }
            },
            methods: {
                fetchGroups: function() {

                    var vue = this;

                    $.ajax({
                        method: "POST",
                        url: url + "admin/user/permissions",
                        dataType: "json"
                    }).done(function(data) {
                        vue.$set("groups", data);
                        vue.groupid = data[0].id;
                        vue.fetchPerms();
                    });

                },
                fetchPerms: function() {

                    var vue = this;

                    $.ajax({
                        method: "POST",
                        url: url + "admin/user/permissions",
                        dataType: "json",
                        data: { method: "getPerm", id: vue.groupid }
                    }).done(function(data) {
                        vue.$set("checked", data);
                    });

                },
                setActive: function(index, id) {
                    this.groupid = id;
                    this.index = index;
                    this.fetchPerms();
                }
            },
        });
    ');
?>
