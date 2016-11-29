<?php
    admin::addButton('
        <a href="'.url::admin('user').'" class="button border">
            '.lang::get('back').'
        </a>
    ');

    $form = new form();

	$field = $form->addTextField('username', '');
	$field->fieldName(lang::get('username'));
	$field->fieldValidate();

	$field = $form->addMediaField('avatar', '', ['ext' => 'jpg,jpeg,png,gif']);
	$field->fieldName(lang::get('avatar'));

	$field = $form->addTextField('email', '');
	$field->fieldName(lang::get('email'));
	$field->fieldValidate('valid_email|required');

	$field = $form->addTextField('password', '', ['v-model="newPassword"']);
	$field->fieldName(lang::get('password'));
	$field->fieldValidate();

	$field = $form->addRadioInlineField('status', 1);
	$field->fieldName(lang::get('status'));
	$field->add(1, lang::get('active'));
	$field->add(0, lang::get('blocked'));

	$field = $form->addSelectField('groupID', 0);
	$field->fieldName(lang::get('user_group'));
	$field->add(0, lang::get('admin'));

	foreach(PermissionModel::getAllFromDb() as $entry) {
		$field->add($entry->id, $entry->name);
	}

	if($form->isSubmit()) {

		if($form->validation()) {

			extension::add('model_beforeInsert', function($data) {
				$password = password_hash($data['password'], PASSWORD_DEFAULT);
				$data['password'] = $password;
				return $data;
			});

			extension::add('model_beforeInsertMeta', function($data) {
				$data['avatar'] = (isset($data['avatar']) && $data['avatar']) ? $data['avatar'] : null;
				return $data;
			});

			$this->model->insert($form->getAll(), true);

			message::success(lang::get('user_added'));

			header('location:'.url::admin('user', ['index']));

		} else {
			echo $form->getErrors();
		}

	}

?>

<div class="columns">
	<div class="md-9 lg-7">
		<?=$form->show(); ?>
	</div>
</div>

<?php
theme::addJSCode('
	new Vue({
		el: "#app",
		data: {
            headline: "'.lang::get('add').'",
			newPassword: ""
		},
		created: function() {
			this.newPassword = randomPassword(12);
		}
	});
');
?>
