<section id="permissions">

    <header>

        <h2><?=lang::get('permissions'); ?></h2>

    </header>

    <?php

        $form = new form();
        $form->setHorizontal(false);

        $form->addFormAttribute('v-on:submit.prevent', 'addGroup');

        $field = $form->addTextField('name', '');
        $field->fieldName(lang::get('name'));
        $field->addAttribute('v-model', 'groupName');
        $field->fieldValidate();

    ?>

    <modal :show.sync="addGroupModal">
        <h3 slot="header"><?=lang::get('add'); ?></h3>
        <div slot="content">
            <?=$form->show(); ?>
        </div>
    </modal>

    <div class="columns">

        <div class="md-3">

            <aside id="aside">

                <nav>
                    <ul>
                        <li v-for="group in groups" :class="$index == index ? 'active' : ''">
                            <a href="#" @click.prevent="setActive($index, group.id)">
                                {{ group.name }}
                            </a>
                            <div v-if="group.id > 0">
                                <a href="<?=url::admin('user', ['permissions', 'delete']); ?>/{{ group.id }}">
                                    <i class="icon icon-trash-a"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <button class="button border" @click="addGroupModal = true"><?=lang::get('add'); ?></button>

            </aside>

        </div>

        <div class="md-9">

            <div v-for="group in groups">
                <div v-if="index == $index">

                    <ul class="list">
                        <li v-for="perm in perms">
                            <h3>{{ $key | lang }}</h3>
                            <div v-for="entry in perm">
                                <span>{{ entry }}</span>
                                <div class="switch">
                                    <input id="{{ $key }}" type="checkbox" :value="$key" :disabled="group.id == 0" v-model="checked">
                                    <label for="{{ $key }}"></label>
                                </div>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>

        </div>

    </div>

</section>

<?php

$perms = [];

foreach(userPerm::getAll() as $key => $val) {
    $perms[strtok($key, '[')][$key] = $val;
}

theme::addJSCode('
    new Vue({
        el: "#permissions",
        data: {
            index: 0,
            groupid: 0,
            groups: [],
            perms: '.json_encode($perms).',
            checked: [],
            addGroupModal: false,
            groupName: ""
        },
        ready: function() {
            this.fetchGroups();
        },
        watch: {
            checked: function() {
                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('user', ['permissions', 'edit']).'",
                    dataType: "json",
                    data: {
                        id: vue.groupid,
                        perms: vue.checked
                    }
                });
            }
        },
        methods: {
            fetchGroups: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('user', ['permissions']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.$set("groups", data);
                        vue.groupid = data[0].id;
                        vue.fetchPerms();
                    }
                });

            },
            addGroup: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('user', ['permissions', 'add']).'",
                    dataType: "text",
                    data: {
                        name: vue.groupName
                    },
                    success: function(data) {
                        vue.fetchGroups();
                        vue.addGroupModal = false;
                        vue.groupName = "";
                    }
                });

            },
            fetchPerms: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('user', ['permissions', 'get']).'",
                    dataType: "json",
                    data: {
                        id: vue.groupid
                    },
                    success: function(data) {
                        vue.$set("checked", data);
                    }
                });

            },
            setActive: function(index, id) {
                this.groupid = id;
                this.index = index;
                this.fetchPerms();
            }
        }
    });
');
?>
