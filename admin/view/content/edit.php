<?php
    admin::addButton('
        <a href="'.url::admin('content').'" class="button border">
            '.lang::get('back').'
        </a>
    ');
    admin::addButton('
        <a href="'.$this->model->getLink().'" target="_blank" class="button border">
            '.lang::get('show').'
        </a>
    ');

    $form = new form();

    $field = $form->addTextField('name', $this->model->name);
    $field->fieldName(lang::get('name'));
    $field->fieldValidate();

    $form->addSave('parentID');
    $field = $form->addRawField('
        <searchbox :current="pageParentName" :currentid="pageParent" except="'.$this->model->name.'" :list="pageAll" val="name" id="id"></searchbox>
        <input type="hidden" v-model="pageParent" name="parentID">
    ');
    $field->fieldName(lang::get('page_parent'));

    if($form->isSubmit()) {

        if($form->validation()) {

		    extension::add('model_beforeSave', function($data) {
                $data['siteURL'] = filter::url($data['name']);
    		    return $data;
		    });

			$this->model->save($form->getAll(), true);

            message::success(lang::get('page_edited'));

		} else {
		    $form->getErrors();
	    }

	}

    $parent = new stdClass();
    $parent->name = '';
    $parent->id = 0;

    if($this->model->parentID) {
        $parent = new PageModel($this->model->parentID);
    }

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

<div class="tabs box">
    <nav>
        <ul class="clear">
            <li class="active">
                <a href="#grid"><?=lang::get('grid'); ?></a>
            </li>
            <li>
                <a href="#<?=strtolower(lang::get('options')); ?>"><?=lang::get('options'); ?></a>
            </li>
        </ul>
    </nav>
    <section>
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
                                <ul class="blocks" :data-row="row" :data-column="key">
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
            <hr>
            <div class="options clear">
                <div class="switch">
                    <input v-model="isEdit" id="isEdit" value="1" type="checkbox">
                    <label for="isEdit"></label>
                    <div><?=lang::get('grid'); ?></div>
                </div>
                <div class="switch">
                    <input v-model="showBlocks" id="showBlocks" value="1" type="checkbox">
                    <label for="showBlocks"></label>
                    <div><?=lang::get('blocks'); ?></div>
                </div>
            </div>
            <div v-show="showBlocks" class="installedBlocks">
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
        <div id="<?=strtolower(lang::get('options')); ?>">
            <?=$form->show(); ?>
        </div>
    </section>
</div>

<?php
theme::addJSCode('
    window.addEventListener("touchmove", function() {});
    new Vue({
        el: "#app",
        data: {
            headline: "'.$this->model->name.'",
            pageAll: '.json_encode(PageModel::getAllFromDb()).',
            isEdit: false,
            showBlocks: false,
            breakpoint: "md",
            minSize: 2,
            maxSize: 12,
            grid: [],
            addColumnSize: 6,
            addColumnRow: null,
            addColumnIndex: null,
            addColumnModal: false,
            pageParent: '.$parent->id.',
            pageParentName: "'.$parent->name.'",
            drakeGrid: null,
            drakeBlocks: null
        },
        mounted: function() {

            this.fetch();

            eventHub.$on("setSearchbox", this.setParent);

        },
        methods: {
            fetch: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['index', 'edit', $this->model->id, 'getContent']).'",
                    dataType: "json",
                    success: function(data) {
                        vue.grid = data;
                        if(!data.length) {
                            vue.isEdit = true;
                        }
                    }
                });

            },
            setParent: function(data) {
                this.pageParent = data.id;
                this.pageParentName = data.name;
            },
            save: function(grid, callback) {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['index', 'edit', $this->model->id, 'saveContent']).'",
                    data: {
                        content: grid
                    },
                    dataType: "json",
                    success: function(data) {
                        vue.grid = data;
                        if(typeof callback === "function") {
                            callback();
                        }
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
                        url: "'.url::admin('content', ['index', 'edit', $this->model->id, 'orderRows']).'",
                        data: {
                            from: from,
                            to: to
                        },
                        success: function() {
                            vue.setDrag();
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

                this.save(this.grid, function() {
                    vue.setDrag();
                });

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

                this.save(this.grid, function() {
                    vue.setDrag();
                });

            },
            addColumn: function() {

                var vue = this;

                this.addColumnModal = false;

                this.grid[this.addColumnRow].splice(this.addColumnIndex, 0, {
                    size: this.addColumnSize
                });

                this.save(this.grid, function() {
                    vue.setDrag();
                });

            },
            removeColumn: function(row, key) {

                var vue = this;

                var entry = this.grid[row][key];

                this.grid[row] = _.filter(this.grid[row], function(obj) {
                    return entry !== obj;
                });

                this.save(this.grid, function() {
                    vue.setDrag();
                });

            },
            deleteBlock: function(row, key, block) {

                var vue = this;

                var entry = this.grid[row][key].blocks[block];

                this.grid[row][key].blocks = _.filter(this.grid[row][key].blocks, function(obj) {
                    return entry !== obj;
                });

                this.save(this.grid);

            }
        },
        watch: {
            showBlocks: function() {

                if(this.showBlocks) {
                    this.setDragBlocks();
                    $(".blocks").addClass("move");
                } else {
                    this.drakeBlocks.destroy();
                    $(".blocks").removeClass("move");
                }

            },
            isEdit: function() {

                if(this.isEdit) {
                    this.setDrag();
                } else {
                    this.drakeGrid.destroy();
                }

            }
        }
    });
');
?>
