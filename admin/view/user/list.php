<section id="user">

    <header>

        <h2 v-html="headline"></h2>

        <div v-if="showSearch" class="search">
            <input type="text" v-model="search">
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

    <?php

        $table = new table();

        var_dump($table);

    ?>

    <data-table :data="tableData" :columns="['email', 'permission', 'status', '']" :headline="headline" :filter-key="search">
        <table-cell>{{ entry.email }}</table-cell>
        <table-cell>
            {{ entry.permissionGroup }}
        </table-cell>
        <table-cell>
            <span v-if="entry.status == 1" data-tooltip="<?=lang::get('active'); ?>" class="status active"></span>
            <span v-else data-tooltip="<?=lang::get('blocked'); ?>" class="status inactive"></span>
        </table-cell>
        <table-cell class="shrink">
            <a href="<?=url::admin('user', ['index', 'edit', '{{ entry.id }}']); ?>" class="icon icon-edit"></a>
        </table-cell>
    </data-table>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#user",
        data: {
            headline: lang["list"],
            checked: [],
            tableData: '.json_encode(UserModel::getAll()).',
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
