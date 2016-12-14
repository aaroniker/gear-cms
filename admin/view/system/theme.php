<section id="themes">
    <div class="columns">
        <div v-for="(entry, key) in themes" class="md-4 sm-6">
            <div class="box">
                <div class="clear">
                    <h3>{{ entry.name }}</h3>
                    <i @click="toggleInfo(key)" :class="{ active: _.includes(info, key) }" class="info icon icon-information-circled"></i>
                </div>
                <div class="screenshot" :style="'background-image: url(<?=config::get('url').'themes/'; ?>' + key + '/screenshot.png);'">
                    <div v-show="_.includes(info, key)">
                        {{ entry.description }}
                        <span>{{ entry.version }}</span>
                        <a v-if="entry.author" :href="entry.url" target="_blank">{{ entry.author }}</a>
                    </div>
                </div>
                <div v-if="entry.active" class="button active"><?=lang::get('active'); ?></div>
                <a v-else class="button border" @click="setActive(key)"><?=lang::get('activate'); ?></a>
            </div>
        </div>
    </div>
</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: lang["theme"],
            info: [],
            themes: '.json_encode(theme::getAll()).'
        },
        created: function() {

            var vue = this;

            eventHub.$on("setHeadline", function(data) {
                vue.headline = data.headline;
            });

        },
        methods: {
            setActive: function(theme) {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('system', ['theme', 'setActive']).'",
                    data: {
                        theme: theme
                    },
                    success: function() {
                        vue.fetch();
                    }
                });

            },
            toggleInfo: function(theme) {

                var vue = this;

                if(_.includes(this.info, theme)) {
                    this.info.splice(this.info.indexOf(theme),1);
                } else {
                    this.info.push(theme);
                }

            },
            fetch: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('system', ['theme', 'get']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.themes = data;
                    }
                });

            }
        }
    });
');
?>
