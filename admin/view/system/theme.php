<div class="columns">
    <div v-for="(entry, key) in themes" class="md-4 sm-6">
        <div class="box">
            <h3>{{ entry.name }}</h3>
            {{ key }}
        </div>
    </div>
</div>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: lang["theme"],
            themes: '.json_encode(theme::getAll()).'
        },
        created: function() {

            var vue = this;

            eventHub.$on("setHeadline", function(data) {
                vue.headline = data.headline;
            });

            vue.fetch();

        },
        methods: {
            fetch: function() {

            }
        }
    });
');
?>
