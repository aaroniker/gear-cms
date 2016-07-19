<section id="user">

    <form class="horizontal">

    <header>

        <h2><?=lang::get('add'); ?></h2>

        <nav>
            <ul>
                <li>
                    <a href="/admin/user" class="button border">
                        <?=lang::get('back'); ?>
                    </a>
                </li>
                <li>
                    <button type="submit" class="button">
                        <?=lang::get('save'); ?>
                    </button>
                </li>
            </ul>
        </nav>

    </header>

    <div class="columns">
        <div class="md-6">

            <div class="form-element">
                <label for="email" class="sm-3"><?=lang::get('email'); ?></label>
                <div class="sm-9">
                    <input type="text" name="email" class="form-field" id="email">
                </div>
            </div>

            <div class="form-element">
                <label for="password" class="sm-3"><?=lang::get('password'); ?></label>
                <div class="sm-9">
                    <input type="password" name="password" class="form-field" id="password">
                </div>
            </div>

        </div>

    </div>

    </form>

</section>
