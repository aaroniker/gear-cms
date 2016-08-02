<section id="permissions">

    <header>

        <h2><?=lang::get('permissions'); ?></h2>

    </header>

    <?php

        $form = new form();

        $field = $form->addTextField('name', '');
        $field->fieldName(lang::get('name'));
        $field->fieldValidate();

    ?>

    <modal :show.sync="showModal">
        <h3 slot="header">custom header</h3>
        <div slot="body">
            <?=$form->show(); ?>
        </div>
    </modal>

    <div class="columns">

        <div class="md-3">

            <button @click="showModal = true">Show Modal</button>

        </div>

        <div class="md-9">

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
                showModal: false
            }
        });
    ');
?>
