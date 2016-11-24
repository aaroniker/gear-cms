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
                        var show = "<span data-tooltip=\''.lang::get('show').'\'><a href=\''.url::admin('extensions', ['blocks', 'show']).'/" + entry.id + "\' class=\'icon icon-navicon-round\'></a></span>";
                        if(entry.active) {
                            return "<nav>" + show + "<span data-tooltip=\''.lang::get('deactivate').'\'><a href=\''.url::admin('extensions', ['blocks', 'setActive']).'/" + entry.id + "\' class=\'ajaxCall icon icon-close\'></a></span></nav>";
                        } else {
                            return "<nav>" + show + "<span data-tooltip=\''.lang::get('activate').'\'><a href=\''.url::admin('extensions', ['blocks', 'setActive']).'/" + entry.id + "\' class=\'ajaxCall icon icon-checkmark\'></a></span></nav>";
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
                    url: "'.url::admin('extensions', ['blocks', 'get']).'",
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
