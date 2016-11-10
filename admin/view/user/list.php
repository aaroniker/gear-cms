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

    <data-table :data="tableData" :columns="columns" :headline="headline" :search="search"></data-table>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#user",
        data: {
            headline: lang["list"],
            tableData: '.json_encode(UserModel::getAll()).',
            columns: {
                email: {
                    title: lang["email"]
                },
                permissionGroup: {
                    title: lang["permission"]
                },
                status: {
                    title: lang["status"],
                    content: function(entry) {
                        if(entry.status == 1) {
                            return "<span data-tooltip=\'" + lang["active"] + "\' class=\'status active\'></span>";
                        } else {
                            return "<span data-tooltip=\'" + lang["blocked"] + "\' class=\'status inactive\'></span>";
                        }
                    }
                },
                action: {
                    title: "",
                    class: "shrink",
                    content: function(entry) {
                        return "<a href=\''.url::admin('user', ['index', 'edit']).'/" + entry.id + "\' class=\'icon icon-edit\'></a>";
                    }
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
