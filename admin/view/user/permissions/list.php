<section id="permissions">

    <header>

        <h2><?=lang::get('permissions'); ?></h2>

    </header>

    <?php

        $form = new form();
        $form->setHorizontal(false);

        $field = $form->addTextField('name', '');
        $field->fieldName(lang::get('name'));
        $field->fieldValidate();

        if($form->isSubmit()) {

            if($form->validation()) {

			    $this->model->insert($form->getAll());

    			echo message::success(lang::get('permission_group_added'));

		    } else {
			    echo $form->getErrors();
		    }

	    }

    ?>

    <modal :show.sync="showModal">
        <h3 slot="header"><?=lang::get('add'); ?></h3>
        <div slot="content">
            <?=$form->show(); ?>
        </div>
    </modal>

    <div class="columns">

        <div class="md-3">

            <nav class="tabs">
                <ul>
                    <li v-for="entry in groups">
                        <a :class="$index == active ? 'active' : ''" href="#" @click.prevent="setActive($index)">
                            {{ entry.name }}
                        </a>
                    </li>
                </ul>
            </nav>

            <button class="button border" @click="showModal = true"><?=lang::get('add'); ?></button>

        </div>

        <div class="md-9">

            <div v-for="entry in groups">
                <div v-if="active == $index">
                    {{ entry.name }}
                </div>
            </div>

            <pre>
                <?php
                    var_dump(userPerm::getAll());
                ?>
            </pre>

        </div>

    </div>

</section>

<?php
    theme::addJSCode('
        new Vue({
            el: "#permissions",
            data: {
                active: 0,
                groups: [],
                showModal: false
            },
            ready: function() {
                this.fetch();
            },
            methods: {
                fetch: function() {

                    var vue = this;

                    $.ajax({
                        method: "POST",
                        url: url + "admin/user/permissions",
                        dataType: "json"
                    }).done(function(data) {
                        vue.$set("groups", data);
                    });

                },
                setActive: function(index){
                    this.active = index
                }
            },
        });
    ');
?>
