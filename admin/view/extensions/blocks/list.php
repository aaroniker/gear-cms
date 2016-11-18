<?php
    admin::$search = true;
?>

<data-table :data="tableData" :columns="columns" :headline="headline" :search="search"></data-table>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: lang["blocks"],
            tableData: '.json_encode(block::getAll()).',
            columns: {
                name: {
                    title: lang["name"]
                },
                description: {
                    title: lang["description"]
                },
                action: {
                    title: "",
                    class: "shrink",
                    content: function(entry) {
                        return "<nav><a href=\''.url::admin('extensions', ['blocks', 'show']).'/" + entry.id + "\' class=\'icon icon-navicon-round\'></a></nav>";
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
