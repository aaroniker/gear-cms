<form action="" method="post">
    <input class="form-field" type="text" name="email" placeholder="<?= __('Email'); ?>">
    <input class="form-field" type="password" name="password" placeholder="<?= __('Password'); ?>">
    <div class="remember">
        <label class="checkbox">
            <input type="checkbox" name="remember" value="1">
            <span><?= __('Stay logged in'); ?></span>
        </label>
    </div>
    <button class="btn block" type="submit">
        <svg>
            <use xlink:href="#lockUI" />
        </svg>
        <?= __('Login'); ?>
    </button>
    <input type="hidden" name="action" value="login">
</form>
