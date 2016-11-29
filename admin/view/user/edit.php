<?php
    admin::addButton('
        <a href="'.url::admin('user').'" class="button border">
            '.lang::get('back').'
        </a>
    ');

    $form = new form();

    $field = $form->addTextField('username', $this->model->username);
	$field->fieldName(lang::get('username'));
    $field->fieldValidate();

    $field = $form->addMediaField('avatar', $this->model->avatar, ['ext' => 'jpg,jpeg,png,gif']);
    $field->fieldName(lang::get('avatar'));

    $field = $form->addTextField('email', $this->model->email);
	$field->fieldName(lang::get('email'));
    $field->fieldValidate('valid_email|required');

    if($this->model->id != user::current()->id) {

    	$field = $form->addRadioInlineField('status', $this->model->status);
        $field->add(1, lang::get('active'));
        $field->add(0, lang::get('blocked'));

    } else {
        $field = $form->addRawField('<p class="static">'.lang::get('status_change_own').'</p>');
    }

    $field->fieldName(lang::get('status'));

    $field = $form->addRawField('
        <div id="pwChange">
            <a v-if="!changePassword" @click="changePassword = true" class="button border">'.lang::get('change_password').'</a>
            <template v-if="changePassword">
                <a @click="changePassword = false" class="button border">'.lang::get('close').'</a>
                <a @click="generate" class="button border">
                    <i class="icon icon-refresh"></i>
                    '.lang::get('generate_password').'
                </a>
                <input v-model="newPassword" type="text" name="password" id="passwordForm" class="form-field">
            </template>
        </div>
    ');
    $field->fieldName(lang::get('password'));

    $form->addSave('password');

    $field = $form->addSelectField('groupID', $this->model->groupID);
    $field->fieldName(lang::get('user_group'));
    $field->add(0, lang::get('admin'));

    foreach(PermissionModel::getAllFromDb() as $entry) {
        $field->add($entry->id, $entry->name);
    }

    if($this->model->id == user::current()->id) {
        $field->addAttribute('disabled');
    }

    if($form->isSubmit()) {

        if($form->validation()) {

		    extension::add('model_beforeSave', function($data) {
                $data['avatar'] = ($data['avatar']) ? $data['avatar'] : null;
                if(!empty($data['password'])) {
    		        $password = password_hash($data['password'], PASSWORD_DEFAULT);
    		        $data['password'] = $password;
                } else {
                    unset($data['password']);
                }
    		    return $data;
		    });

			$this->model->save($form->getAll(), true);

            message::success(lang::get('user_edited'));

		} else {
		    $form->getErrors();
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
            headline: "'.lang::get('edit').'",
            newPassword: "",
            changePassword: false
        },
        watch: {
            changePassword: function(bool) {
                if(bool) {
                    this.newPassword = randomPassword(12);
                } else {
                    this.newPassword = "";
                }
            }
        },
        methods: {
            generate: function() {
                this.newPassword = randomPassword(12);
            }
        }
    });
');
?>
