<section id="content">

    <header>

        <h2>{{{ headline | lang }}}</h2>

        <div class="search">
            <input type="text" v-model="filterKey">
        </div>

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
                    <i @click="toggleSearchBox" class="icon icon-close-round"></i>
                </div>
                <template v-if="pageParent != 0">
                    <div class="result">
                        <span class="active">{{ parent }}</span>
                    </div>
                </template>
                <template v-if="this.searchBox.length >= 2">
                    <ul class="result" v-if="searchFilter.length">
                        <li v-for="entry in pageAll | filterBy searchBox" @click="pageParent = entry.id, pageParentName = entry.name">
                            {{ entry.name }}
                        </li>
                    </ul>
                    <div class="result" v-if="!searchFilter.length">'.lang::get('no_results').'</div>
                </template>
                <template v-if="pageParent != 0">
                    <div class="result">
                        <span @click="pageParent = 0, pageParentName = \'\'">{{ "page_parent_no" | lang }}</span>
                    </div>
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
        <h3 v-drop="move(0, $dropdata)"><?=option::get('sitename'); ?></h3>
        <ul>
            <item v-for="model in pageTree | filterBy filterKey" :model="model"></item>
        </ul>
        <template v-if="!pageTree">
            <?=lang::get('no_results'); ?>
        </template>
    </div>

</section>

<template id="item-template">
    <li>
        <div class="entry clear" v-drop="move(model.id, $dropdata)">
            <div v-drag="{id: model.id}" class="info">
                <span>{{ model.name }}</span>
                <small>{{ model.siteURL }}</small>
            </div>
            <span v-if="model.home" class="setHome" data-tooltip="<?=lang::get('page_home'); ?>"><i class="icon icon-ios-home"></i></span>
            <span v-else class="setHome" @click="setHome(model.id)"><i class="inactive icon icon-ios-home-outline"></i></span>
            <a href="<?=url::admin('content', ['index', 'delete', '{{ model.id }}']); ?>" class="icon delete ajax icon-ios-trash-outline"></a>
            <a href="<?=url::admin('content', ['index', 'edit', '{{ model.id }}']); ?>" class="icon edit icon-edit"></a>
        </div>
        <ul v-if="model.children">
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
        },
        methods: {
            move: function(parentID, data) {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['index', 'move']).'",
                    data: {
                        parent: parentID,
                        id: data.id
                    },
                    success: function() {
                        vue.$dispatch("eventFetch");
                    }
                });

            },
            setHome: function(id) {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['index', 'setHome']).'",
                    data: {
                        id: id
                    },
                    success: function() {
                        vue.$dispatch("eventFetch");
                    }
                });

            }
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
            searchBox: "",
            filterKey: ""
        },
        created: function() {

            var vue = this;

            $(document).on("fetch", function() {
                vue.fetch();
            });
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
            move: function(parentID, data) {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['index', 'move']).'",
                    data: {
                        parent: parentID,
                        id: data.id
                    },
                    success: function() {
                        vue.fetch();
                    }
                });

            },
            toggleSearchBox: function() {
                this.searchBoxShow = !this.searchBoxShow;
            }
        },
        events: {
            eventFetch: function() {
                this.fetch();
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
