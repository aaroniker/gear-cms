<?php

class UserModel extends model {

	protected $id;
	protected $password;
	protected $email;
	
	public function __construct($id = 0) {

		$this->_model = 'user';
		
		$this->_metaData = [
		];
		
		if($id)
			$this->load($id);
			
		return $this;

	}
	
}

?>