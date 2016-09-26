<?php

class form {

    protected $method;
    protected $action;

    protected $horizontal = true;

    protected $formAttributes = [];

    protected $toSave = [];
    protected $return = [];

    protected $fields = [];

    protected $tabs = [];
    protected $lastTab = '';

    protected $errors = [];

    public static $rules = [];

    public function __construct($action = '', $method = 'post') {

        $this->method = $method;
        $this->action = $action;

        $this->addFormAttribute('action', $this->action);
        $this->addFormAttribute('method', $this->method);

    }

    private function addElement($name, $object, $save = true) {

        if($save) {
            $this->toSave[$name] = $object;
        }

        if(count($this->tabs)) {
            $this->tabs[$this->lastTab][$name] = $object;
        } else {
            $this->return[$name] = $object;
        }

        return $object;

    }

    private function addField($name, $value, $class, $attributes = [], $save = true) {

        $field = new $class($name, type::super($name, 'string', $value), $attributes);
        $this->addElement($name, $field, $save);

        return $field;

    }

    public function addRawField($value, $attributes = []) {
        return $this->addField('form_'.count($this->return), $value, 'formRaw', $attributes);
	}

    public function addTextField($name, $value, $attributes = []) {

        $attributes['type'] = 'text';

        return $this->addField($name, $value, 'formInput', $attributes);

    }

    public function addPasswordField($name, $value, $attributes = []) {

        $attributes['type'] = 'password';

        return $this->addField($name, $value, 'formInput', $attributes);

    }

    public function addRadioField($name, $value, $attributes = []) {
        return $this->addField($name, $value, 'formRadio', $attributes);
    }

    public function addRadioInlineField($name, $value, $attributes = []) {

        $attributes['inline'] = true;

        return $this->addField($name, $value, 'formRadio', $attributes);

    }

    public function addCheckboxField($name, $value, $attributes = []) {
        return $this->addField($name, $value, 'formCheckbox', $attributes);
    }

    public function addCheckboxInlineField($name, $value, $attributes = []) {

        $attributes['inline'] = true;

        return $this->addField($name, $value, 'formCheckbox', $attributes);

    }

    public function addSwitchField($name, $value, $attributes = []) {

        $attributes['switch'] = true;

        return $this->addField($name, $value, 'formCheckbox', $attributes);

    }

    public function addSwitchInlineField($name, $value, $attributes = []) {

        $attributes['switch'] = true;
        $attributes['inline'] = true;

        return $this->addField($name, $value, 'formCheckbox', $attributes);

    }

    public function addHiddenField($name, $value, $attributes = []) {

        $attributes['type'] = 'hidden';

        return $this->addField($name, $value, 'formInput', $attributes);

    }

    public function addSelectField($name, $value, $attributes = []) {
        return $this->addField($name, $value, 'formSelect', $attributes);
    }

    public function addTextareaField($name, $value, $attributes = []) {
        return $this->addField($name, $value, 'formTextarea', $attributes);
    }

    public function addFormAttribute($name, $value) {

        if(isset($this->formAttributes[$name])) {
            $this->formAttributes[$name] = $this->formAttributes[$name].' '.$value;
        } else {
            $this->formAttributes[$name] = $value;
        }

        return $this;

    }

    public function getAll() {

        $return = [];

        foreach($this->toSave as $name => $object) {

            $value = type::super($name);

            $return[$name] = (is_array($value)) ? implode('|', $value) : $value;

        }

        return $return;

    }

    public static function addRule($name, $rule) {
        self::$rules[$name] = $rule;
    }

    public static function getRules() {
        return self::$rules;
    }

    public function isSubmit() {
        return isset($_POST['save']);
    }

    public function validation() {

        $valid = validate($this->getAll(), $this->getRules());

        if($valid[0]) {
            return true;
        } else {
            $this->errors[] = $valid[1];
        }

        return false;

    }

    public function getErrors() {

        if(count($this->errors)) {
            return implode(PHP_EOL, $this->errors);
        } else {
            return '';
        }

    }

    public function setHorizontal($bool) {
        $this->horizontal = $bool;
    }

    public function addTab($name) {

        $this->tabs[$name] = [];

        $this->lastTab = $name;

    }

    private function loopFields($data, $return) {

        foreach($data as $show) {

			if($show->getAttribute('type') == 'hidden') {
				$hidden[] = $show->get();
				continue;
			}

            $parent = $show->getAttribute('parent');
            $show->delAttribute('parent');

			$return[] = '<div class="form-element" '.$parent.'>';
			$return[] = '<label class="sm-3" for="'.$show->getAttribute('id').'">'.$show->fieldName.'</label>';
			$return[] = '<div class="sm-9">'.$show->get().'</div>';
			$return[] = '</div>';

		}

        return $return;

    }

    public function show() {

        if($this->horizontal) {
            $this->addFormAttribute('class', 'horizontal');
        }

        $return = [];
		$hidden = [];

        $return[] = '<form'.html_convertAttribute($this->formAttributes).'>'.PHP_EOL;

        $return = $this->loopFields($this->return, $return);

        if(count($this->tabs)) {

            $return[] = '<div class="tabs">';
            $return[] = '<nav>';
            $return[] = '<ul class="clear">';
            foreach($this->tabs as $name => $content) {
                $return[] = '<li><a href="#tab-'.filter::url($name).'">'.$name.'</a></li>';
            }
            $return[] = '</ul>';
            $return[] = '</nav>';

            $return[] = '<section>';
            foreach($this->tabs as $name => $content) {
                $return[] = '<div id="tab-'.filter::url($name).'">';
                $return = $this->loopFields($content, $return);
                $return[] = '</div>';
            }
            $return[] = '</section>';
            $return[] = '</div>';

        }

        $return[] = '
            <button class="button fl-right" name="save" type="submit">'.lang::get('save').'</button>
            <div class="clear"></div>
        ';

        $return[] = implode(PHP_EOL, $hidden);

        $return[] = '</form>'.PHP_EOL;

        return implode(PHP_EOL, $return);

    }

}

?>
