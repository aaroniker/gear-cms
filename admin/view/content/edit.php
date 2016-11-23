<?php
    admin::addButton('
        <a href="'.url::admin('content').'" class="button border">
            '.lang::get('back').'
        </a>
        <a href="'.$this->model->getLink().'" target="_blank" class="button border">
            '.lang::get('show').'
        </a>
    ');
?>

<modal v-if="addColumnModal" @close="addColumnModal = false">
    <h3 slot="header"><?=lang::get('add'); ?></h3>
    <div slot="content" class="clear">
        <div class="form-element">
            <div class="form-select">
                <select v-model="addColumnSize">
                    <option v-for="n in _.range(minSize, maxSize + 1)" :value="n">{{ n }}</option>
                </select>
            </div>
        </div>
        <a @click="addColumn" class="button fl-right"><?=lang::get('add'); ?></a>
    </div>
</modal>

<section id="grid" :class="isEdit ? 'isEdit' : 'noEdit'">
    <div class="rows">
        <div class="row" v-for="(columns, row) in grid">
            <i class="icon icon-arrow-move move"></i>
            <i v-if="!columns.length" @click="removeRow(row)" class="icon icon-ios-trash-outline remove"></i>
            <div @click="addColumnModal = true, addColumnRow = row, addColumnIndex = 0" class="addColumn" data-tooltip="<?=lang::get('new_column'); ?>">
                <i class="icon icon-android-add"></i>
            </div>
            <div class="columns">
                <div v-for="(column, key) in columns" :class="breakpoint + '-' + column.size">
                    <div class="box">
                        <div class="blocks">
                            {{ column.size }}
                        </div>
                        <div class="edit">
                            <i v-if="column.size < maxSize" @click="size(row, key, 1)" class="plus icon icon-android-add-circle"></i>
                            <i v-if="column.size > minSize" @click="size(row, key, -1)" class="minus icon icon-android-remove-circle"></i>
                            <i @click="removeColumn(row, key)" class="remove icon icon-android-cancel"></i>
                        </div>
                        <div @click="addColumnModal = true, addColumnRow = row, addColumnIndex = (key + 1)" class="addColumn" data-tooltip="<?=lang::get('new_column'); ?>">
                            <i class="icon icon-android-add"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @click="addRow" class="row new">
        <?=lang::get('new_row'); ?>
    </div>
</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: "'.$this->model->name.'",
            isEdit: true,
            breakpoint: "md",
            minSize: 2,
            maxSize: 12,
            grid: [
                [
                    {
                        size: 6
                    },
                    {
                        size: 3
                    },
                    {
                        size: 3
                    }
                ],
                [
                    {
                        size: 4
                    },
                    {
                        size: 8
                    }
                ]
            ],
            addColumnSize: 6,
            addColumnRow: null,
            addColumnIndex: null,
            addColumnModal: false
        },
        mounted: function() {

            this.setDrag();

        },
        methods: {
            setDrag: function() {

                var vue = this;
                var from = null;

                var drake = dragula([$("#grid > .rows")[0]], {
                    moves: function(el, container, handle) {
                        return handle.classList.contains("move");
                    },
                    mirrorContainer: $("#grid")[0]
                });

                drake.on("drag", function(element, source) {
                    var index = [].indexOf.call(element.parentNode.children, element);
                    from = index;
                });

                drake.on("drop", function(element, target, source, sibling) {
                    var index = [].indexOf.call(element.parentNode.children, element);
                    vue.grid.splice(index, 0, vue.grid.splice(from, 1)[0]);
                });

            },
            size: function(row, key, num) {

                var size = parseInt(this.grid[row][key].size);
                var newSize = size + parseInt(num);

                if(newSize >= this.minSize && newSize <= this.maxSize) {
                    this.grid[row][key].size = newSize;
                }

            },
            addRow: function() {

                Array.prototype.pushArray = function(arr) {
                    this.push.apply(this, arr);
                };

                this.grid.pushArray([[]]);

            },
            removeRow: function(row) {

                var entry = this.grid[row];

                this.grid = _.filter(this.grid, function(obj) {
                    return entry !== obj;
                });

            },
            addColumn: function() {

                this.addColumnModal = false;

                this.grid[this.addColumnRow].splice(this.addColumnIndex, 0, {
                    size: this.addColumnSize
                });

            },
            removeColumn: function(row, key) {

                var entry = this.grid[row][key];

                this.grid[row] = _.filter(this.grid[row], function(obj) {
                    return entry !== obj;
                });

            }
        }
    });
');
?>
