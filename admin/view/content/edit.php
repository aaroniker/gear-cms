<?php
    admin::addButton('
        <a href="'.url::admin('content').'" class="button border">
            '.lang::get('back').'
        </a>
    ');
?>

content

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: "'.$this->model->name.'"
        }
    });
');
?>
