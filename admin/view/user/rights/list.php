<section id="rights">

    <header>

        <h2><?=lang::get('rights'); ?></h2>

    </header>

    <button @click="showModal = true">Show Modal</button>

    <modal :show.sync="showModal">
        <h3 slot="header">custom header</h3>
        <div slot="body">
            body
        </div>
    </modal>

    <pre>
        <?php
            var_dump(userPerm::getAll());
        ?>
    </pre>

</section>

<?php
    theme::addJSCode('
        new Vue({
            el: "#rights",
            data: {
                showModal: false
            }
        });
    ');
?>
