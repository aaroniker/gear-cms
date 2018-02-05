<form action="" method="post">
    <input class="form-field" type="text" name="email" placeholder="<?= __('Email'); ?>">
    <input class="form-field" type="password" name="password" placeholder="<?= __('Password'); ?>">
    <button class="btn block" type="submit">
        <?= __('Login'); ?>
    </button>
    <div class="remember">
        <label class="checkbox">
            <input type="checkbox" name="remember" value="1">
            <div></div>
            <span><?= __('Stay logged in'); ?></span>
        </label>
    </div>
    <input type="hidden" name="action" value="login">
</form>
