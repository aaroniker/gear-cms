<section id="media">

    <header>

        <h2>{{{ headline | lang }}}</h2>

        <div v-if="showSearch" class="search">
            <input type="text" v-model="search">
        </div>

        <nav>
            <ul>
                <li>
                    <a @click="addDirModal = true" class="button border">
                        <?=lang::get('add_dir'); ?>
                    </a>
                </li>
                <li>
                    <a @click="uploadModal = true" class="button">
                        <?=lang::get('upload'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <?php

        $form = new form();
        $form->setHorizontal(false);

        $form->addFormAttribute('v-on:submit.prevent', 'addDir');

        $field = $form->addTextField('name', '');
        $field->fieldName(lang::get('name'));
        $field->addAttribute('v-model', 'dirName');
        $field->fieldValidate();

        $field = $form->addRawField('<p class="static">{{ path }}<span class="text-light" v-if="dirName">{{ dirName }}</span></p>');
        $field->fieldName(lang::get('path'));

    ?>

    <modal :show.sync="addDirModal">
        <h3 slot="header"><?=lang::get('add_dir'); ?></h3>
        <div slot="content">
            <?=$form->show(); ?>
        </div>
    </modal>

    <modal :show.sync="uploadModal">
        <h3 slot="header"><?=lang::get('upload'); ?></h3>
        <div slot="content">
            <div id="upload">
                <div class="drop">

                    <i class="icon icon-ios-download-outline"></i>

                    <input type="file" name="files[]" id="file" multiple>
                    <label for="file" class="button">
                        <?=lang::get('choose_files'); ?>
                    </label>

                </div>
            </div>
        </div>
    </modal>

    <file-table :data="tableData" :columns="['name', 'size']" :headline="headline" :filter-key="search"></data-table>

</section>

<?php
theme::addJSCode('
    $("#upload").gearUpload({

    });
    new Vue({
        el: "#media",
        data: {
            headline: "media",
            checked: [],
            path: "/",
            tableData: '.json_encode(media::getAll('/')).',
            search: "",
            showSearch: true,
            addDirModal: false,
            uploadModal: false,
            dirName: ""
        },
        events: {
            checked: function(data) {
                this.checked = data;
            },
            path: function(data) {
                this.path = data;
            },
            headline: function(data) {
                this.headline = data.headline;
                this.showSearch = data.showSearch;
            }
        },
        methods: {
            addDir: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['media', 'addDir']).'",
                    dataType: "text",
                    data: {
                        name: vue.dirName,
                        path: vue.path
                    },
                    success: function(data) {
                        vue.$broadcast("fetchData");
                        vue.addDirModal = false;
                        vue.dirName = "";
                    }
                });

            }
        }
    });
');
?>
