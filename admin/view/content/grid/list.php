<?php
    admin::addButton('
        <a href="'.url::admin('content').'" class="button border">
            '.lang::get('pages').'
        </a>
    ');

    admin::addButton('
        <a @click="addGridModal = true" class="button">
            '.lang::get('add').'
        </a>
    ');

    admin::$search = true;

    $form = new form();
    $form->setHorizontal(false);

    $form->addFormAttribute('v-on:submit.prevent', 'addGrid');

    $field = $form->addTextField('name', '');
    $field->fieldName(lang::get('name'));
    $field->addAttribute('v-model', 'gridName');
    $field->fieldValidate();
?>

<modal v-if="addGridModal" @close="addGridModal = false">
    <h3 slot="header"><?=lang::get('add'); ?></h3>
    <div slot="content">
        <?=$form->show(); ?>
    </div>
</modal>

<data-table :data="tableData" :columns="columns" :headline="headline" :search="search"></data-table>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: lang["grid_templates"],
            tableData: '.json_encode(GridModel::getAll()).',
            columns: {
                name: {
                    title: lang["name"]
                },
                action: {
                    title: "",
                    class: "shrink",
                    content: function(entry) {
                        return "<nav><a href=\''.url::admin('content', ['grid', 'edit']).'/" + entry.id + "\' class=\'icon icon-edit\'></a></nav>";
                    }
                }
            },
            search: "",
            showSearch: true,
            addGridModal: false,
            gridName: ""
        },
        created: function() {

            var vue = this;

            eventHub.$on("setHeadline", function(data) {
                vue.headline = data.headline;
                vue.showSearch = data.showSearch;
            });

            $(document).on("fetch", function() {
                vue.fetch();
            });

        },
        methods: {
            fetch: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['grid', 'get']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.tableData = data;
                    }
                });

            },
            addGrid: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['grid', 'add']).'",
                    data: {
                        name: vue.gridName
                    },
                    success: function(data) {
                        vue.fetch();
                        vue.addGridModal = false;
                        vue.gridName = "";
                    }
                });

            }
        }
    });
');
?>
