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
                <ul></ul>
            </div>
        </div>
    </modal>

    <file-table :data="tableData" :columns="['name', 'size']" :headline="headline" :filter-key="search"></data-table>

</section>

<?php
theme::addJSCode('
    function addFile(id, file) {

        var name = file.name;
        var size = file.size;

        var template = "<li id=\'file" + id + "\'><p><h4>" + name + " (" + size + ")</h4><small>" + lang["status"] + ": <strong>" + lang["waiting"] + "</strong></small></p><div class=\'progress\'><div></div></div></li>";

        $("#upload").find("ul").prepend(template);

    }

    function updateFile(id, status, message) {
        $("#file" + id).find("strong").html(message).addClass(status);
    }

    function updateProgress(id, percent) {
        $("#file" + id).find(".progress").children("div").width(percent);
    }

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
        watch: {
            uploadModal: function() {
                if(this.uploadModal) {
                	$("#upload").gearUpload({
                		url: url + "admin/content/media/upload",
                		eventBeforeUpload: function(id){
                			updateFile(id, "info", lang["uploading"]);
                		},
                        eventNewFile: function(id, file){
                			addFile(id, file);
                		},
                		eventUploadProgress: function(id, percent){
                			var percentStr = percent + "%";
                			updateProgress(id, percentStr);
                		},
                		eventUploadSuccess: function(id, data){
                			updateFile(id, "success", lang["complete"]);
                			updateProgress(id, "100%");
                		},
                		eventUploadError: function(id, message){
                			updateFile(id, "error", message);
                		}
                	});
                }
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
