<section id="content">

    <header>

        <h2>{{{ headline | lang }}}</h2>

        <nav>
            <ul>
                <li>
                </li>
            </ul>
        </nav>

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

                <nav>
                    <ul v-if="menus > 0">
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
                            <item v-for="model in pageTree" :model="model"></item>
                        </ul>
                        <template v-if="!pageTree">
                            <?=lang::get('no_results'); ?>
                        </template>
                    </div>

                </div>
            </div>

        </div>

    </div>

</section>

<template id="item-template">
    <li id="item_{{ model.id }}">
        <div class="entry clear">
            <div class="info">
                <span>{{ model.name }}</span>
                <small>{{ model.siteURL }}</small>
            </div>
            <a href="<?=url::admin('content', ['index', 'delete', '{{ model.id }}']); ?>" class="icon delete ajax icon-ios-trash-outline"></a>
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
            console.log($("#menuList > ul").sortableListsToArray());
        },
        ignoreClass: "click"
    };

    new Vue({
        el: "#content",
        data: {
            headline: "menus",
            menus: [],
            items: [],
            pageTree: '.json_encode(PageModel::getAll()).',
            pageAll: '.json_encode(PageModel::getAllFromDb()).',
            addMenuModal: false,
            menuName: "",
            menuID: 0
        },
        ready: function() {
            this.fetchMenus();
        },
        methods: {
            fetchMenus: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['menus']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.menus = data;
                        vue.setActive(data[0].id);
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
                        $("#menuList > ul").sortableLists(options);
                    }
                });

            },
            addMenu: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['menus', 'add']).'",
                    dataType: "json",
                    data: {
                        name: vue.menuName
                    },
                    success: function(data) {
                        vue.fetchMenus();
                        vue.addMenuModal = false;
                        vue.menuName = "";
                    }
                });

            },
            setActive: function(id) {
                this.menuID = id;
                this.fetchItems();
            }
        }
    });
');
?>
