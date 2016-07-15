<?php

class userController extends controller {

	public function __construct() {
		$this->model = new UserModel;
	}

	public function index() {
		
		if(ajax::is()) {
			$array[] = [
				'id' => 1,
				'email' => 'test'
			];
			$array[] = [
				'id' => 2,
				'email' => 'test2'
			];
			ajax::addReturn(json_encode($array));
		}
		
		admin::vue('user/list.js');

		include(dir::view('user/list.php'));
	
	}

	public function edit($id = 0) {

		if ($id > 0) {
			
			$model = new UserModel($id);

			include(dir::view('user/edit.php'));

		}

	}

}

?>