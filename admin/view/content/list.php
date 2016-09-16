<section id="content">

    <header>

        <h2>{{{ headline | lang }}}</h2>

        <nav>
            <ul>
                <li>
                    <a @click="addPageModal = true" class="button">
                        <?=lang::get('add'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <?php

        $form = new form();
        $form->setHorizontal(false);

        $form->addFormAttribute('v-on:submit.prevent', 'addPage');

        $field = $form->addTextField('name', '');
        $field->fieldName(lang::get('name'));
        $field->addAttribute('v-model', 'pageName');
        $field->fieldValidate();

        $field = $form->addRawField('
        <div class="form-select">
            <div class="choose" @click="toggleSearchBox">{{ parent }}</div>
            <div v-if="searchBoxShow" class="searchBox">
                <div class="search">
                    <input type="text" v-model="searchBox">
                </div>
                <template v-if="this.searchBox.length > 2">
                    <ul class="result" v-if="searchFilter.length">
                        <li v-for="entry in pageAll | filterBy searchBox" @click="pageParent = entry.id, pageParentName = entry.name">
                            {{ entry.name }}
                        </li>
                    </ul>
                    <div class="result" v-if="!searchFilter.length">'.lang::get('no_results').'</div>
                </template>
            </div>
        </div>
        ');
        $field->fieldName(lang::get('page_parent'));

    ?>

    <modal :show.sync="addPageModal">
        <h3 slot="header"><?=lang::get('add'); ?></h3>
        <div slot="content">
            <?=$form->show(); ?>
        </div>
    </modal>

    <div id="pageList" class="box">
        <ul>
            <item v-for="model in pageTree" :model="model"></item>
        </ul>
    </div>

</section>

<template id="item-template">
    <li>
        <div>{{ model.name }}</div>
        <ul v-if="model.children.length != 0">
            <item v-for="model in model.children" :model="model"></item>
        </ul>
    </li>
</template>

<?php
theme::addJSCode('
    Vue.component("item", {
        template: "#item-template",
        props: {
            model: Object
        }
    });

    new Vue({
        el: "#content",
        data: {
            headline: "pages",
            addPageModal: false,
            pageName: "",
            pageParent: 0,
            pageParentName: "",
            pageTree: '.json_encode(PageModel::getAll()).',
            pageAll: '.json_encode(PageModel::getAllFromDb()).',
            searchBoxShow: false,
            searchBox: ""
        },
        methods: {
            fetch: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['index', 'get']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.pageAll = data.all;
                        vue.pageTree = data.tree;
                    }
                });

            },
            addPage: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['index', 'add']).'",
                    dataType: "text",
                    data: {
                        name: vue.pageName,
                        parent: vue.pageParent
                    },
                    success: function(data) {
                        vue.fetch();
                        vue.addPageModal = false;
                        vue.pageName = "";
                    }
                });

            },
            toggleSearchBox: function() {
                this.searchBoxShow = !this.searchBoxShow;
            }
        },
        watch: {
            pageParent: function() {
                this.searchBoxShow = false;
            }
        },
        computed: {
            searchFilter: function() {
                return this.$eval("pageAll | filterBy searchBox");
            },
            parent: function() {
                if(this.pageParent == 0) {
                    return lang["page_parent_no"];
                } else {
                    return this.pageParentName;
                }
            }
        }
    });
');
?>
