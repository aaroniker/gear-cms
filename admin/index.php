<?php
	
	include('../gear/classes/dir.php');

	new dir('../');
	
	$env = 'admin';
	
	include(dir::gear('bootstrap.php'));
	
	theme::addCSS('admin/assets/css/style.css', true);
	
	theme::addJS('https://code.jquery.com/jquery-3.0.0.min.js');
	theme::addJS('https://cdn.jsdelivr.net/vue/1.0.26/vue.min.js');
	theme::addJS('admin/assets/js/app.js', true);
	
	if(userLogin::isLogged()) {
		
		admin::addMenu(lang::get('dashboard'), '');
		admin::addMenu(lang::get('content'), 'content');
		
		admin::addMenu(lang::get('user'), 'user');
		admin::addSubmenu(lang::get('list'), '', 'user');
		
		admin::addMenu(lang::get('plugins'), 'plugins');
		admin::addMenu(lang::get('system'), 'system');
		
	
		include(dir::view('head.php'));
			
		include(dir::view('header.php'));
	
		echo config::get('content');
	
		include(dir::view('footer.php'));
	
	} else {
	
		include(dir::view('head.php'));
	
		echo config::get('content');
	
		include(dir::view('footer.php'));
	
	}
	
?>