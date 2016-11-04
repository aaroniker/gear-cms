<section id="plugins">

    <header>

        <h2 v-html="headline"></h2>

        <div v-if="showSearch" class="search">
            <input type="text" v-model="search">
        </div>

    </header>

    <data-table :data="tableData" :columns="['name', 'description', '']" :headline="headline" :search="search"></data-table>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#plugins",
        data: {
            headline: lang["plugins"],
            tableData: '.json_encode(plugin::getAll()).',
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
