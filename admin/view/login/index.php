<section id="login">

    <div class="form">

        <a href="http://gearcms.org" class="logo" target="_blank">
            <img src="<?=url::assets('img/logo.svg'); ?>" alt="Gear CMS Logo">
        </a>

        <div class="content box">

            <form action="" method="post">

                <?=config::get('system'); ?>

                <div id="messages"></div>

                <div class="form-element">
                    <input type="text" name="email" id="email" value="<?=type::super('email'); ?>" placeholder="<?=lang::get('email'); ?>" class="form-field">
                </div>

                <div class="form-element">
                    <input type="password" name="password" id="password" value="<?=type::super('password'); ?>" placeholder="<?=lang::get('password'); ?>" class="form-field">
                </div>

                <div class="action clear">

                    <button type="submit" name="login" value="1" class="button">
                        <?=lang::get('login'); ?>
                    </button>

                </div>

            </form>

        </div>

        <a href="<?=url::base(); ?>"><?=lang::get('go_to_website'); ?></a>

    </div>

</section>
