<section id="user">

    <header>

        <h2><?=lang::get('list'); ?></h2>

        <div class="search">
            <input type="text" v-model="searchString">
        </div>

        <nav>
            <ul>
                <li>
                    <a href="/admin/user/add" class="button">
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
                tableData: [],
                tableColumns: ["email"],
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
            }
        });
    ', true);
?>
