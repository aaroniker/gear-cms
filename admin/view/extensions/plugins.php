<section id="plugins">

    <header>

        <h2 v-html="headline"></h2>

        <div v-if="showSearch" class="search">
            <input type="text" v-model="search">
        </div>

    </header>

    <data-table :data="tableData" :columns="columns" :headline="headline" :search="search"></data-table>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#plugins",
        data: {
            headline: lang["plugins"],
            tableData: '.json_encode(plugin::getAll()).',
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
