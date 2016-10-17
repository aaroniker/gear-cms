<section id="content">

    <header>

        <h2><?=$this->model->name; ?></h2>

        <nav>
            <ul>
                <li>
                    <a href="<?=url::admin('content'); ?>" class="button border">
                        <?=lang::get('back'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    content

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#content",
        data: {
            pageTree: '.json_encode(PageModel::getAll()).',
            pageAll: '.json_encode(PageModel::getAllFromDb()).'
        }
    });
');
?>
