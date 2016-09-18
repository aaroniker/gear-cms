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

    <div id="menuList" class="box">
        <ul>
            <item v-for="model in pageTree" :model="model"></item>
        </ul>
    </div>

</section>

<template id="item-template">
    <li id="item_{{ model.id }}">
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

    var options = {
        insertZone: 30,
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
            fetch: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['index', 'get']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.pageTree = data;
                        $("#menuList > ul").sortableLists(options);
                    }
                });

            }
        }
    });
');
?>
