<?php
    admin::addButton('
        <a href="'.url::admin('content').'" class="button border">
            '.lang::get('back').'
        </a>
    ');
?>

<section id="grid">
    <div class="row" v-for="(columns, row) in grid">
        <div class="columns">
            <div v-for="(column, key) in columns" :class="breakpoint + '-' + column.size">
                <div class="box">
                    <div class="size">
                        <i v-if="column.size < 12" @click="size(row, key, 1)" class="plus icon icon-plus-circled"></i>
                        <i v-if="column.size > 2" @click="size(row, key, -1)" class="minus icon icon-minus-circled"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: "'.$this->model->name.'",
            breakpoint: "md",
            grid: [
                [
                    {
                        size: "6"
                    },
                    {
                        size: "3"
                    },
                    {
                        size: "3"
                    }
                ],
                [
                    {
                        size: "4"
                    },
                    {
                        size: "8"
                    }
                ]
            ]
        },
        created: function() {
            var vue = this;
        },
        methods: {
            size: function(row, key, num) {

                var size = parseInt(this.grid[row][key].size);
                var newSize = size + parseInt(num);

                if(newSize > 1 && newSize < 13) {
                    this.grid[row][key].size = newSize;
                }

            }
        }
    });
');
?>
