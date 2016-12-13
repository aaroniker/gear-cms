<?php
    admin::addButton('
        <a href="'.url::admin('content', ['grid']).'" class="button border">
            '.lang::get('back').'
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

<div class="box">
    <div id="grid" :class="isEdit ? 'isEdit' : 'noEdit'">
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
                            <ul class="blocks move" :data-row="row" :data-column="key">
                                <li v-for="(block, blockKey) in column.blocks" :data-id="block.id" :data-name="block.name" class="button">
                                    {{ block.name }}
                                    <a @click="deleteBlock(row, key, blockKey)" class="icon icon-ios-trash-outline delBlock"></a>
                                </li>
                            </ul>
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
        <div class="installedBlocks">
            <h3><?=lang::get('blocks'); ?></h3>
            <?php
                if(count(block::getInstalled())) {
                    echo '<ul class="clear unstyled">';
                    foreach(block::getInstalled() as $block) {
                        echo '<li class="button" data-id="'.$block['id'].'" data-name="'.$block['name'].'">'.$block['name'].'</li>';
                    }
                    echo '</ul>';
                } else {
                    echo lang::get('no_results');
                }
            ?>
        </div>
    </div>
</div>

<?php
theme::addJSCode('
    window.addEventListener("touchmove", function() {});
    new Vue({
        el: "#app",
        data: {
            headline: "'.$this->model->name.'",
            isEdit: true,
            breakpoint: "md",
            minSize: 2,
            maxSize: 12,
            grid: [],
            addColumnSize: 6,
            addColumnRow: null,
            addColumnIndex: null,
            addColumnModal: false,
            drakeGrid: null,
            drakeBlocks: null
        },
        mounted: function() {
            var vue = this;
            vue.fetch();
            setTimeout(function() {
                vue.setDrag();
                vue.setDragBlocks();
            }, 100);
        },
        methods: {
            fetch: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['grid', 'edit', $this->model->id, 'getContent']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.grid = data;
                        vue.setDrag();
                        vue.setDragBlocks();
                    }
                });

            },
            save: function(grid, callback) {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['grid', 'edit', $this->model->id, 'saveContent']).'",
                    data: {
                        content: grid
                    },
                    dataType: "json",
                    success: function(data) {
                        vue.grid = data;
                        if(typeof callback === "function") {
                            callback();
                        }
                        vue.setDrag();
                        vue.setDragBlocks();
                    }
                });

            },
            setDrag: function() {

                var vue = this;
                var from = null;

                this.drakeGrid = dragula([$("#grid > .rows")[0]], {
                    moves: function(el, container, handle) {
                        return handle.classList.contains("move");
                    },
                    mirrorContainer: $("#grid")[0]
                });

                this.drakeGrid.on("drag", function(element, source) {
                    var index = $(element).parent().children(".row").index($(element));
                    from = index;
                });

                this.drakeGrid.on("drop", function(element, target, source, sibling) {
                    var to = $(element).parent().children(".row").index($(element));
                    $.ajax({
                        method: "POST",
                        url: "'.url::admin('content', ['grid', 'edit', $this->model->id, 'orderRows']).'",
                        data: {
                            from: from,
                            to: to
                        }
                    });
                });

            },
            setDragBlocks: function() {

                var vue = this;

                var containers = $(".blocks").toArray();
                containers = containers.concat($(".installedBlocks > ul").toArray());

                this.drakeBlocks = dragula(containers, {
                    copy: function (el, source) {
                        return source === $(".installedBlocks > ul")[0]
                    },
                    accepts: function (el, target) {
                        return target !== $(".installedBlocks > ul")[0]
                    }
                });

                this.drakeBlocks.on("drop", function(element, target, source, sibling) {

                    var id = $(element).data("id");
                    var name = $(element).data("name");

                    $(".blocks").each(function(i) {

                        var row = $(this).data("row");
                        var column = $(this).data("column");

                        vue.grid[row][column].blocks = new Array();

                        $(this).children("li").each(function(index) {
                            var id = $(this).data("id");
                            var name = $(this).data("name");
                            vue.grid[row][column].blocks.splice(index, 0, {
                                id: id,
                                name: name
                            });
                        });

                    });

                    vue.save(vue.grid, function() {
                        if(!$(source).hasClass("blocks")) {
                            $(element).remove();
                        }
                    });

                });

            },
            size: function(row, key, num) {

                var vue = this;

                var size = parseInt(this.grid[row][key].size);
                var newSize = size + parseInt(num);

                if(newSize >= this.minSize && newSize <= this.maxSize) {
                    this.grid[row][key].size = newSize;
                }

                this.save(this.grid);

            },
            addRow: function() {

                Array.prototype.pushArray = function(arr) {
                    this.push.apply(this, arr);
                };

                this.grid.pushArray([[]]);

            },
            removeRow: function(row) {

                var vue = this;

                var entry = this.grid[row];

                this.grid = _.filter(this.grid, function(obj) {
                    return entry !== obj;
                });

                this.save(this.grid);

            },
            addColumn: function() {

                var vue = this;

                this.addColumnModal = false;

                this.grid[this.addColumnRow].splice(this.addColumnIndex, 0, {
                    size: this.addColumnSize
                });

                this.save(this.grid);

            },
            removeColumn: function(row, key) {

                var vue = this;

                var entry = this.grid[row][key];

                this.grid[row] = _.filter(this.grid[row], function(obj) {
                    return entry !== obj;
                });

                this.save(this.grid);

            },
            deleteBlock: function(row, key, block) {

                var vue = this;

                var entry = this.grid[row][key].blocks[block];

                this.grid[row][key].blocks = _.filter(this.grid[row][key].blocks, function(obj) {
                    return entry !== obj;
                });

                this.save(this.grid);

            }
        }
    });
');
?>
