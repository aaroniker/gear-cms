<?php
    admin::$search = true;
?>

<data-table :data="tableData" :columns="columns" :headline="headline" :search="search"></data-table>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: lang["plugins"],
            tableData: '.json_encode(plugin::getAll()).',
            columns: {
                name: {
                    title: lang["name"]
                },
                description: {
                    title: lang["description"]
                },
                action: {
                    title: ""
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
