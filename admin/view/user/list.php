<section id="user">

    <header>

        <h2>{{ headline | lang }}</h2>

        <div class="search">
            <input type="text" v-model="searchString">
        </div>

        <nav>
            <ul>
                <li>
                    <a href="<?=config::get('url').'admin/user/index/add'; ?>" class="button">
                        <?=lang::get('add'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <data-table :data="tableData" :columns="tableColumns" :filter-key="searchString"></data-table>

</section>

<?php
    theme::addJSCode('
        new Vue({
            el: "#user",
            data: {
                headline: "list",
                checked: [],
                tableData: [],
                tableColumns: ["email", "status"],
                searchString: ""
            },
            ready: function() {
                this.fetch();
            },
            methods: {
                fetch: function() {

                    var vue = this;

                    $.ajax({
                        method: "POST",
                        url: url + "admin/user",
                        dataType: "json",
                        data: {}
                    }).done(function(data) {
                        vue.$set("tableData", data);
                    });

                }
            },
            events: {
                "checked": function (data) {
                    this.checked = data;
                    if(data.length) {
                        this.headline = data.length + " " + lang["selected"];
                    } else {
                        this.headline = "list";
                    }
                }
            }
        });
    ', true);
?>
