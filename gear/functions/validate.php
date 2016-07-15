<?php
	
	function validate($data, $addRules = []) {
		
		$rules = [
			'email' => 'valid_email'
		];
		
		$rulesArray = array_merge($rules, $addRules);
		
		validate::set_field_name("email", lang::get('email'));
		validate::set_field_name("password", lang::get('password'));
		
		$validate = new validate();
		
		$validate->validation_rules($rulesArray);
		
		$is_valid = ($validate->run($data) === false) ? false : true;
		
		return [$is_valid, $validate->get_readable_errors()];
		
	}
	
	function file_rename($path, $filename) {

		if($pos = strrpos($filename, '.')) {
			$name = substr($filename, 0, $pos);
			$ext = substr($filename, $pos);
		} else {
			$name = $filename;
		}
		
		$newpath = $path.'/'.$filename;
		$newname = $filename;
		$counter = 0;
		
		while (file_exists($newpath)) {
			$newname = $name .'_'. $counter . $ext;
			$newpath = $path.'/'.$newname;
			$counter++;
		}
		
		return $newname;
	
	}
	
?>