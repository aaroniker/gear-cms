<section id="login">

    <div class="form">

        <a href="" class="logo">
            <img src="<?=config::get('url').'admin/assets/img/logo.svg'; ?>" alt="Gear CMS Logo">
        </a>

        <form action="" method="post">

            <?=config::get('system'); ?>

            <div class="form-element">
                <input type="text" name="email" id="email" value="<?=type::super('email'); ?>" placeholder="<?=lang::get('email'); ?>" class="form-field">
            </div>

            <div class="form-element">
                <input type="password" name="password" id="password" value="<?=type::super('password'); ?>" placeholder="<?=lang::get('password'); ?>" class="form-field">
            </div>

            <div class="action">

                <button type="submit" name="login" value="1" class="button">
                    <?=lang::get('login'); ?>
                </button>

                <div class="remember">
                    <div class="switch">
                        <input type="checkbox" name="remember" id="remember" value="1">
                        <label for="remember"></label>
                        <div><?=lang::get('remember'); ?></div>
                    </div>
                </div>

                <div class="clear"></div>

            </div>

        </form>

    </div>

</section>
