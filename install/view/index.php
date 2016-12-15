<section id="install">

    <div class="form">

        <a href="http://gearcms.org" class="logo" target="_blank">
            <img src="<?=url::assets('img/logo.svg'); ?>" alt="Gear CMS Logo">
        </a>

        <div class="content box">

            <nav>
                <ul class="unstyled">
                    <?php
                        foreach($steps as $name) {
                            $active = ($name == $step) ? 'class="active"' : '';
                            echo '<li '.$active.'>'.lang::get($name).'</li>';
                        }
                    ?>
                </ul>
            </nav>

            <?=config::get('system'); ?>

            <div id="messages"></div>

            <?php
                if($step == 'database') {

                    $form = new form();

                    $field = $form->addTextField('host', 'localhost');
                    $field->fieldName(lang::get('host'));
                    $field->fieldValidate();

                    $field = $form->addTextField('user', '');
                    $field->fieldName(lang::get('user'));
                    $field->fieldValidate();

                    $field = $form->addPasswordField('password', '');
                    $field->fieldName(lang::get('password'));
                    $field->fieldValidate();

                    $field = $form->addTextField('database', '');
                    $field->fieldName(lang::get('database'));
                    $field->fieldValidate();

                    $field = $form->addTextField('prefix', '');
                    $field->fieldName(lang::get('prefix'));

                    if($form->isSubmit()) {

                        if($form->validation()) {

                            $array = $form->getAll();

                            $error = false;

                            try {
                                $pdo = new PDO('mysql:host=' . $array['host'] . ';dbname=' . $array['database'] . ';charset=utf8', $array['user'], $array['password'], [
                                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
                                ]);
                            } catch (exception $e) {
                                message::error($e->getMessage());
                                $error = true;
                            }

                            if(!$error) {

                                $pdo->query("
                                    CREATE TABLE IF NOT EXISTS `entry` (
                                        `id` int(20) NOT NULL AUTO_INCREMENT,
                                        `type` varchar(255) NOT NULL,
                                        `name` varchar(255) NOT NULL,
                                    PRIMARY KEY (`id`)
                                    ) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
                                    CREATE TABLE IF NOT EXISTS `entry_meta` (
                                        `meta_id` int(20) NOT NULL AUTO_INCREMENT,
                                        `entry_id` int(20) NOT NULL,
                                        `meta_key` varchar(255) NOT NULL,
                                        `meta_value` longtext NOT NULL,
                                    PRIMARY KEY (`meta_id`)
                                    ) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;
                                    CREATE TABLE IF NOT EXISTS `logs` (
                                        `log_id` int(20) NOT NULL AUTO_INCREMENT,
                                        `log_entry_type` varchar(255) NOT NULL,
                                        `log_entry_id` int(20) NOT NULL,
                                        `log_user_id` int(20) NOT NULL,
                                        `log_action` varchar(255) NOT NULL,
                                        `log_datetime` datetime NOT NULL,
                                    PRIMARY KEY (`log_id`)
                                    ) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;
                                    CREATE TABLE IF NOT EXISTS `options` (
                                        `option_id` int(20) NOT NULL AUTO_INCREMENT,
                                        `option_key` varchar(255) NOT NULL,
                                        `option_value` longtext NOT NULL,
                                    PRIMARY KEY (`option_id`)
                                    ) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
                                    CREATE TABLE IF NOT EXISTS `user` (
                                        `id` int(20) NOT NULL AUTO_INCREMENT,
                                        `email` varchar(255) NOT NULL,
                                        `password` varchar(255) NOT NULL,
                                        `status` int(11) NOT NULL DEFAULT '0',
                                    PRIMARY KEY (`id`)
                                    ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
                                    CREATE TABLE IF NOT EXISTS `user_meta` (
                                        `meta_id` int(20) NOT NULL AUTO_INCREMENT,
                                        `user_id` int(20) NOT NULL,
                                        `meta_key` varchar(255) NOT NULL,
                                        `meta_value` longtext NOT NULL,
                                    PRIMARY KEY (`meta_id`)
                                    ) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;
                                    CREATE TABLE IF NOT EXISTS `visits` (
                                        `visit_id` int(20) NOT NULL AUTO_INCREMENT,
                                        `visit_ip` varchar(39) NOT NULL,
                                        `visit_hits` int(20) NOT NULL,
                                        `visit_date` date NOT NULL,
                                    PRIMARY KEY (`visit_id`)
                                    ) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
                                ");

                                config::add('host', $array['host'], true);
                                config::add('user', $array['user'], true);
                                config::add('password', $array['password'], true);
                                config::add('database', $array['database'], true);
                                config::add('prefix', $array['prefix'], true);
                                config::save();

                                header('Location: ?step=informations');
                                exit();

                            }

                        } else {
                            $form->getErrors();
                        }

                    }

                    echo $form->show();

                } elseif($step == 'informations') {

                    $form = new form();

                    $field = $form->addTextField('sitename', '');
                    $field->fieldName(lang::get('sitename'));
                    $field->fieldValidate();

                    $field = $form->addTextField('siteurl', '');
                    $field->fieldName(lang::get('siteurl'));
                    $field->fieldValidate();

                    $field = $form->addTextField('timezone', config::get('timezone'));
                    $field->fieldName(lang::get('timezone'));
                    $field->fieldValidate();

                    $form->addRawField('<hr>');

                    $field = $form->addTextField('username', '');
                    $field->fieldName(lang::get('username'));
                    $field->fieldValidate();

                    $field = $form->addTextField('email', '');
                    $field->fieldName(lang::get('email'));
                	$field->fieldValidate('valid_email|required');

                    $field = $form->addPasswordField('password', '');
                    $field->fieldName(lang::get('password'));
                    $field->fieldValidate();

                    if($form->isSubmit()) {

                        if($form->validation()) {

                            $array = $form->getAll();

                            $DB = config::get('DB');

                            $pdo = new PDO('mysql:host=' . $DB['host'] . ';dbname=' . $DB['database'] . ';charset=utf8', $DB['user'], $DB['password'], [
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
                            ]);

                            $db = new sql($pdo);

                            $db->setPrefix($DB['prefix']);

                            function db($pdoReturn = false) {
                                global $db, $pdo;
                                if($pdoReturn) {
                                    return $pdo;
                                }
                                return $db;
                            }

                            unset($DB);

                            config::add('url', $array['siteurl'], true);
                            config::add('timezone', $array['timezone'], true);
                            config::save();

                            option::set('sitename', $array['sitename']);

                            $model = new UserModel();

                            $model->insert([
                                'username' => $array['username'],
                                'email' => $array['email'],
                                'password' => password_hash($array['password'], PASSWORD_DEFAULT)
                            ], true);

                            header('Location: ?step=finished');
                            exit();

                        } else {
                            $form->getErrors();
                        }

                    }

                    echo $form->show();

                } elseif($step == 'finished') {

                    echo '
                        <p>'.lang::get('install_success').'</p>
                        <a href="../admin">'.lang::get('admin_login').'</a> -
                        <a href="http://gearcms.org" target="_blank">'.lang::get('gear_website').'</a> -
                        <a href="http://forum.gearcms.org" target="_blank">'.lang::get('gear_forum').'</a>
                    ';

                } else {

                    $form = new form();

                    $field = $form->addSelectField('lang', '');
                    $field->fieldName(lang::get('language'));
                    $field->fieldValidate();
                    $field->add(null, '-');
                    foreach(lang::getAll() as $key => $val) {
                        $field->add($key, $val);
                    }

                    if($form->isSubmit()) {

                        if($form->validation()) {

                            $array = $form->getAll();

                            config::add('lang', $array['lang'], true);
                            config::save();

                            header('Location: ?step=database');
                            exit();

                        } else {
                            $form->getErrors();
                        }

                    }

                    echo $form->show();

                }

            ?>

        </div>

        <a href="http://gearcms.org/dokumentation" target="_blank"><?=lang::get('documentation'); ?></a>

    </div>

</section>
