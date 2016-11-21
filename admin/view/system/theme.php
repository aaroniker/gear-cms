<div class="columns">
    <?php foreach(theme::getAll() as $key => $theme): ?>
    <div class="md-4 sm-6">
        <div class="box">
            <h3><?=$theme['name']; ?></h3>
            <?=$key; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: lang["theme"]
        },
        created: function() {

            var vue = this;

            eventHub.$on("setHeadline", function(data) {
                vue.headline = data.headline;
            });

        }
    });
');
?>
