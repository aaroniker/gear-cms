<g-form>
    <input type="hidden" name="action" value="login">
    <div class="form-element">
        <input class="form-field" type="text" name="email" placeholder="<?= __('Email'); ?>">
    </div>
    <div class="form-element">
        <input class="form-field" type="password" name="password" placeholder="<?= __('Password'); ?>">
    </div>
    <div class="form-element">
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
</g-form>
