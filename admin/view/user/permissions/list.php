<?php

    $form = new form();
    $form->setHorizontal(false);

    $form->addFormAttribute('v-on:submit.prevent', 'addGroup');

    $field = $form->addTextField('name', '');
    $field->fieldName(lang::get('name'));
    $field->addAttribute('v-model', 'groupName');
    $field->fieldValidate();

?>

<modal v-if="addGroupModal" @close="addGroupModal = false">
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
                    <li v-for="group in groups" :class="group.id == groupID ? 'active' : ''">
                        <a @click.prevent="setActive(group.id)">
                            {{ group.name }} <span v-if="group.id > 0">({{ group.countUser }})</span>
                        </a>
                        <div class="action" v-if="group.id > 0">
                            <a class="delete" :href="'<?=url::admin('user', ['permissions', 'delete']); ?>/' + group.id">
                                <i class="icon icon-ios-trash-outline"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <button class="button border" @click="addGroupModal = true"><?=lang::get('add'); ?></button>

        </aside>

    </div>

    <div class="md-9">

        <div id="permissions" v-for="group in groups">
            <div v-if="groupID == group.id">

                <ul class="list">
                    <li v-for="(perm, key) in perms">
                        <div class="box">
                            <h3>{{ key | lang }}</h3>
                            <div v-for="(entry, key) in perm">
                                <span>{{ entry }}</span>
                                <div class="switch">
                                    <input :id="key" type="checkbox" :value="key" :disabled="group.id == 0" v-model="checked">
                                    <label :for="key"></label>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>
        </div>

    </div>

</div>

<?php

$perms = [];

foreach(userPerm::getAll() as $key => $val) {
    $perms[strtok($key, '[')][$key] = $val;
}

theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: "'.lang::get('permissions').'",
            groupID: 0,
            groups: [],
            perms: '.json_encode($perms).',
            checked: [],
            addGroupModal: false,
            groupName: ""
        },
        mounted: function() {
            this.fetchGroups();
        },
        watch: {
            checked: function() {
                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('user', ['permissions', 'edit']).'",
                    data: {
                        id: vue.groupID,
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
                        vue.groups = data;
                        vue.setActive(data[0].id);
                    }
                });

            },
            addGroup: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('user', ['permissions', 'add']).'",
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
                        id: vue.groupID
                    },
                    success: function(data) {
                        vue.checked = data;
                    }
                });

            },
            setActive: function(id) {
                this.groupID = id;
                this.fetchPerms();
            }
        }
    });
');
?>
