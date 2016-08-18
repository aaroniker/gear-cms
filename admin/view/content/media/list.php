<section id="media">

    <header>

        <h2>{{ headline | lang }}</h2>

        <div v-if="showSearch" class="search">
            <input type="text" v-model="search">
        </div>

        <nav>
            <ul>
                <li>
                    <a href="" class="button border">
                        <?=lang::get('add_dir'); ?>
                    </a>
                </li>
                <li>
                    <a href="" class="button">
                        <?=lang::get('upload'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <file-table :data="tableData" :columns="['name', 'size']" :headline="headline" :filter-key="search"></data-table>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#media",
        data: {
            headline: "media",
            checked: [],
            path: "/",
            tableData: '.json_encode(file_list('/')).',
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
