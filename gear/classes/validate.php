<?php

class validate extends GUMP {
	
	public function get_readable_errors($convert_to_string = false, $field_class = 'gump-field', $error_class = 'gump-error-message') {
	
		if(empty($this->errors)) {
			return ($convert_to_string) ? null : array();
		}
		
		$resp = array();

		foreach($this->errors as $e) {
	
			$field = ucwords(str_replace($this->fieldCharsToRemove, chr(32), $e['field']));
			$param = $e['param'];
			
			if(array_key_exists($e['field'], self::$fields))
				$field = self::$fields[$e['field']];
				
			if(isset(self::$fields[$param]))
				$param = self::$fields[$param];
				
			$resp[] = sprintf(lang::get($e['rule']), $field, $param);
			
		}
		
		return message::error($resp);

	}
	
    protected function validate_equalsfield($field, $input, $param = null) {
		
        if(($input[$field] == $input[$param])) {
        	return;
        }

        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param,
        );
		
    }
		
}

?>