<?php
    admin::$search = true;
?>

<data-table :data="tableData" :columns="columns" :headline="headline" :search="search"></data-table>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: lang["logs"],
            tableData: '.json_encode(log::getAll(false)).',
            columns: {
                log_entry_type: {
                    title: lang["type"]
                },
                log_action: {
                    title: lang["action"]
                },
                log_datetime: {
                    title: lang["time"]
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
