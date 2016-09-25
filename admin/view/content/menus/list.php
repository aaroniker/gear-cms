<section id="content">

    <header>

        <h2>{{{ headline | lang }}}</h2>

    </header>

    <?php

        $form = new form();
        $form->setHorizontal(false);

        $form->addFormAttribute('v-on:submit.prevent', 'addMenu');

        $field = $form->addTextField('name', '');
        $field->fieldName(lang::get('name'));
        $field->addAttribute('v-model', 'menuName');
        $field->fieldValidate();

    ?>

    <modal :show.sync="addMenuModal">
        <h3 slot="header"><?=lang::get('add'); ?></h3>
        <div slot="content">
            <?=$form->show(); ?>
        </div>
    </modal>

    <div class="columns">

        <div class="md-3">

            <aside id="aside">

                <nav v-if="menuID">
                    <ul>
                        <li v-for="menu in menus" :class="menu.id == menuID ? 'active' : ''">
                            <a @click.prevent="setActive(menu.id)">
                                {{ menu.name }}
                            </a>
                            <div class="action">
                                <a class="delete" href="<?=url::admin('content', ['menus', 'delete', '{{ menu.id }}']); ?>">
                                    <i class="icon icon-ios-trash-outline"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <button class="button border" @click="addMenuModal = true"><?=lang::get('add'); ?></button>

            </aside>

        </div>

        <div class="md-9">

            <div v-for="menu in menus">
                <div v-if="menuID == menu.id">
                    <div id="menuList">
                        <ul>
                            <item v-for="model in items" :model="model"></item>
                        </ul>
                    </div>
                </div>
            </div>

            <template v-if="menuID">

                <hr>

                <div class="box">
                    <h3><?=lang::get('add'); ?></h3>
                    <?php

                        $form = new form();

                        $form->addFormAttribute('v-on:submit.prevent', 'addMenuItem');

                        $form->addTab(lang::get('menu_page_link'));

                        $field = $form->addTextField('name', '');
                        $field->fieldName(lang::get('name'));
                        $field->addAttribute('v-model', 'menuItemName');
                        $field->fieldValidate();

                        $field = $form->addRawField('
                        <div class="form-select">
                            <div class="choose" @click="toggleSearchBox">{{ searchBoxTitle }}</div>
                            <div v-if="searchBoxShow" class="searchBox">
                                <div class="search">
                                    <input type="text" v-model="searchBox">
                                    <i @click="toggleSearchBox" class="icon icon-close-round"></i>
                                </div>
                                <template v-if="menuItemPageID != 0">
                                    <div class="result">
                                        <span class="active">{{ menuItemPage }}</span>
                                    </div>
                                </template>
                                <template v-if="this.searchBox.length >= 1">
                                    <ul class="result" v-if="searchFilter.length">
                                        <li v-for="entry in pageAll | filterBy searchBox" @click="menuItemPageID = entry.id, menuItemPage = entry.name">
                                            {{ entry.name }}
                                        </li>
                                    </ul>
                                    <div class="result" v-if="!searchFilter.length">'.lang::get('no_results').'</div>
                                </template>
                                <template v-if="menuItemPageID != 0">
                                    <div class="result">
                                        <span @click="menuItemPageID = 0, menuItemPage = \'\'">{{ "page_parent_no" | lang }}</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        ');
                        $field->fieldName(lang::get('page'));
                        $field->fieldValidate();

                        $form->addTab(lang::get('menu_link'));

                        $field = $form->addTextField('name', '');
                        $field->fieldName(lang::get('name'));
                        $field->addAttribute('v-model', 'menuItemName');
                        $field->fieldValidate();

                        $field = $form->addTextField('link', '');
                        $field->fieldName(lang::get('link'));
                        $field->addAttribute('v-model', 'menuItemLink');
                        $field->fieldValidate();

                        echo $form->show();

                    ?>
                </div>

            </template>

        </div>

    </div>

</section>

<template id="item-template">
    <li id="item_{{ model.id }}">
        <div class="entry clear">
            <div class="info">
                <span>{{ model.name }}</span>
                <small>{{ model.pageName }} - {{ model.pageURL }}</small>
            </div>
            <a href="<?=url::admin('content', ['menus', 'delItem', '{{ model.id }}']); ?>" class="icon delete ajax icon-ios-trash-outline"></a>
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
        }
    });

    var options = {
        insertZone: 50,
        placeholderClass: "placeholder",
        hintClass: "hint",
        baseClass: "",
        complete: function(el) {
            var array = $("#menuList > ul").sortableListsToArray();
            $.ajax({
                method: "POST",
                url: "'.url::admin('content', ['menus', 'move']).'",
                dataType: "json",
                data: {
                    array: array
                }
            });
        },
        ignoreClass: "icon"
    };

    new Vue({
        el: "#content",
        data: {
            headline: "menus",
            menus: [],
            items: [],
            pageAll: '.json_encode(PageModel::getAllFromDb()).',
            addMenuModal: false,
            menuName: "",
            menuID: 0,
            searchBoxShow: false,
            searchBox: "",
            menuItemName: "",
            menuItemPage: "",
            menuItemPageID: 0
        },
        ready: function() {
            this.fetchMenus();
        },
        created: function() {

            var vue = this;

            $(document).on("fetch", function() {
                vue.fetchItems();
            });

        },
        methods: {
            fetchMenus: function(id) {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['menus']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.menus = data;
                        if(data.length) {
                            vue.setActive(data[0].id);
                        }
                    }
                });

            },
            fetchItems: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['menus', 'get']).'",
                    dataType: "json",
                    data: {
                        id: vue.menuID
                    },
                    success: function(data) {
                        vue.items = data;
                        if(!$("#menuList").hasClass("loaded")) {
                            $("#menuList").addClass("loaded");
                            $("#menuList > ul").sortableLists(options);
                        }
                        setTabs();
                    }
                });

            },
            addMenu: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['menus', 'add']).'",
                    data: {
                        name: vue.menuName
                    },
                    success: function(data) {
                        vue.addMenuModal = false;
                        vue.fetchMenus();
                        vue.menuName = "";
                    }
                });

            },
            addMenuItem: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['menus', 'addItem']).'",
                    data: {
                        id: vue.menuID,
                        name: vue.menuItemName,
                        pageID: vue.menuItemPageID
                    },
                    success: function(data) {
                        vue.fetchItems();
                        vue.menuItemName = "";
                        vue.menuItemPage = "";
                        vue.menuItemPageID = 0;
                    }
                });

            },
            setActive: function(id) {
                this.menuID = id;
                this.fetchItems();
            },
            toggleSearchBox: function() {
                this.searchBoxShow = !this.searchBoxShow;
            }
        },
        watch: {
            menuItemPage: function() {
                this.searchBoxShow = false;
            }
        },
        computed: {
            searchFilter: function() {
                return this.$eval("pageAll | filterBy searchBox");
            },
            searchBoxTitle: function() {
                if(this.menuItemPageID == 0) {
                    return lang["page_no"];
                } else {
                    return this.menuItemPage;
                }
            }
        }
    });
');
?>
