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

    <div id="menuList">
        <ul>
            <item v-for="model in pageTree" :model="model"></item>
        </ul>
        <template v-if="!pageTree">
            <?=lang::get('no_results'); ?>
        </template>
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
            pageTree: '.json_encode(PageModel::getAll()).',
            pageAll: '.json_encode(PageModel::getAllFromDb()).'
        },
        ready: function() {
            $("#menuList > ul").sortableLists(options);
        },
        methods: {
        }
    });
');
?>
