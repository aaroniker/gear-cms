<section id="plugins">

    <header>

        <h2>{{{ headline | lang }}}</h2>

        <div v-if="showSearch" class="search">
            <input type="text" v-model="search">
        </div>

    </header>

    <data-table :data="tableData" :columns="['name', 'status']" :headline="headline" :filter-key="search">
        <table-cell>{{ entry.name }}</table-cell>
        <table-cell>
            <span v-if="entry.status == 1" class="status active"></span>
            <span v-else class="status inactive"></span>
        </table-cell>
    </data-table>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#plugins",
        data: {
            headline: "list",
            checked: [],
            tableData: [],
            search: "",
            showSearch: true
        },
        events: {
            checked: function (data) {
                this.checked = data;
            },
            headline: function (data) {
                this.headline = data.headline;
                this.showSearch = data.showSearch;
            }
        }
    });
');
?>
