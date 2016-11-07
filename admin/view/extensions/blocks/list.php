<section id="blocks">

    <header>

        <h2 v-html="headline"></h2>

        <div v-if="showSearch" class="search">
            <input type="text" v-model="search">
        </div>

    </header>

    <data-table :data="tableData" :columns="columns" :headline="headline" :search="search"></data-table>

    <?php /*
    <data-table :data="tableData" :columns="['name', 'description', '']" :headline="headline" :filter-key="search">
        <table-cell>{{ entry.name }}</table-cell>
        <table-cell>{{ entry.description }}</table-cell>
        <table-cell class="shrink">
            <a href="<?=url::admin('extensions', ['blocks', 'show', '{{ entry.id }}']); ?>" class="icon icon-navicon-round"></a>
        </table-cell>
    </data-table>
    */ ?>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#blocks",
        data: {
            headline: lang["blocks"],
            tableData: '.json_encode(block::getAll()).',
            columns: {
                "name": {
                    title: lang["name"]
                },
                "description": {
                    title: lang["description"]
                },
                "action": {
                    title: ""
                }
            },
            search: "",
            showSearch: true
        },
        created: function() {

            var vue = this;

            eventHub.$on("setHeadline", function(data) {
                vue.headline = data.headline;
                vue.showSearch = data.showSearch;
            });

        }
    });
');
?>
