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
                    title: lang["name"],
                    content: function(entry) {
                        return entry.name + " <small class=\'text-light\'>" + entry.version + "</small>";
                    }
                },
                description: {
                    title: lang["description"]
                },
                author: {
                    title: lang["author"],
                    content: function(entry) {
                        return "<a class=\'text-dark\' href=\'" + entry.url + "\' target=\'_blank\'>" + entry.author + "</a>";
                    }
                },
                action: {
                    title: "",
                    class: "shrink",
                    content: function(entry) {
                        if(entry.active) {
                            return "<nav><span data-tooltip=\''.lang::get('deactivate').'\'><a href=\''.url::admin('extensions', ['index', 'setActive']).'/" + entry.id + "\' class=\'ajaxCall icon icon-close\'></a></span></nav>";
                        } else {
                            return "<nav><span data-tooltip=\''.lang::get('activate').'\'><a href=\''.url::admin('extensions', ['index', 'setActive']).'/" + entry.id + "\' class=\'ajaxCall icon icon-checkmark\'></a></span></nav>";
                        }
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

            $(document).on("fetch", function() {
                vue.fetch();
            });

        },
        methods: {
            fetch: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('extensions', ['index', 'get']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.tableData = data;
                    }
                });

            }
        }
    });
');
?>
