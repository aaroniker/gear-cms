<section id="dashboard">

    <header>

        <h2><?=lang::get('overview'); ?></h2>

    </header>

    dash

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#dashboard",
        data: {
        }
    });
');
?>
