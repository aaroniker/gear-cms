<section id="user">

    <header>

        <h2>{{ headline | lang }}</h2>

        <div class="search">
            <input type="text" v-model="searchString">
        </div>

        <nav>
            <ul>
                <li>
                    <a href="<?=url::admin('user', ['index', 'add']); ?>" class="button">
                        <?=lang::get('add'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <data-table :url="fetchUrl" :columns="['email', 'status']" :filter-key="searchString">
        <table-cell>{{ entry.email }}</table-cell>
        <table-cell>
            <span v-if="entry.status == 1">{{ 'active' | lang }}</span>
            <span v-else>{{ 'blocked' | lang }}</span>
        </table-cell>
    </data-table>

</section>

<?php
    theme::addJSCode('
        new Vue({
            el: "#user",
            data: {
                headline: "list",
                checked: [],
                fetchUrl: "'.url::admin('user').'",
                searchString: ""
            },
            ready: function() {
                this.$broadcast("fetch");
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
