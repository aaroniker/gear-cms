<section id="user">

    <header>

        <h2>{{ headline | lang }}</h2>

        <div class="search">
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

    <data-table :data="tableData" :columns="['email', 'status', '']" :headline="headline" :filter-key="search">
        <table-cell>{{ entry.email }}</table-cell>
        <table-cell>
            <span v-if="entry.status == 1">{{ 'active' | lang }}</span>
            <span v-else>{{ 'blocked' | lang }}</span>
        </table-cell>
        <table-cell class="shrink">
            <a href="<?=url::admin('user', ['index', 'edit', '{{ entry.id }}']); ?>">
                <?=lang::get('edit'); ?>
            </a>
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
                tableData: '.json_encode(UserModel::getAllFromDb()).',
                search: ""
            },
            events: {
                checked: function (data) {
                    this.checked = data;
                },
                headline: function (data) {
                    this.headline = data;
                }
            }
        });
    ', true);
?>
