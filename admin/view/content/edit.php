<?php
    admin::addButton('
        <a href="'.url::admin('content').'" class="button border">
            '.lang::get('back').'
        </a>
    ');
?>

<section id="grid">
    <div class="rows">
        <div class="row" v-for="(columns, row) in grid">
            <i class="icon icon-arrow-move move"></i>
            <div class="addColumn" data-tooltip="<?=lang::get('new_column'); ?>">
                <i class="icon icon-android-add"></i>
            </div>
            <div class="columns">
                <div v-for="(column, key) in columns" :class="breakpoint + '-' + column.size">
                    <div class="box">
                        <div class="blocks">
                            {{ column.size }}
                        </div>
                        <div class="edit">
                            <i class="drag icon icon-arrow-move"></i>
                            <i v-if="column.size < 12" @click="size(row, key, 1)" class="plus icon icon-android-add-circle"></i>
                            <i v-if="column.size > 2" @click="size(row, key, -1)" class="minus icon icon-android-remove-circle"></i>
                        </div>
                        <div class="addColumn" data-tooltip="<?=lang::get('new_column'); ?>">
                            <i class="icon icon-android-add"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row new">
        <?=lang::get('new_row'); ?>
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
        mounted: function() {

            var vue = this;

            $("#grid > .rows").sortable({
                placeholder: "placeholder",
                handle: ".move",
                helper: "clone",
                axis: "y"
            });

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
