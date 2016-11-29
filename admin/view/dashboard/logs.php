<?php
    admin::$search = true;
?>

<ul>
<?php
    $logs = log::getAll(false);
    if($logs) {
        foreach($logs as $log) {
            $icon = ($log->log_action == 'edit') ? '<i class="icon icon-edit"></i>' : '<i class="icon icon-plus"></i>';
            $user = new UserModel($log->log_user_id);
            echo '
                <li>
                    <span data-tooltip="'.$log->log_entry_id.'">'.$icon.' '.lang::get($log->log_entry_type).'</span>
                    von '.$user->username.' - '.time_since($log->log_datetime).'
                </li>
            ';
        }
    } else {
        echo '<li>'.lang::get('no_results').'</li>';
    }
?>
</ul>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: lang["logs"],
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

        }
    });

');
?>
