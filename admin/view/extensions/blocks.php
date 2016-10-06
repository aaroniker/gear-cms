<section id="blocks">

    <header>

        <h2>{{{ headline | lang }}}</h2>

        <div v-if="showSearch" class="search">
            <input type="text" v-model="search">
        </div>

    </header>

    <data-table :data="tableData" :columns="['name', '']" :headline="headline" :filter-key="search">
        <table-cell>{{ entry.name }}</table-cell>
        <table-cell class="shrink">
            info
        </table-cell>
    </data-table>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#blocks",
        data: {
            headline: "list",
            checked: [],
            tableData: '.json_encode(block::getAll()).',
            search: "",
            showSearch: true
        },
        events: {
            checked: function(data) {
                this.checked = data;
            },
            headline: function(data) {
                this.headline = data.headline;
                this.showSearch = data.showSearch;
            }
        }
    });
');
?>
