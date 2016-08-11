<?php

class url {

    public static function admin($page = '', $params = []) {

        $return = config::get('url').'admin';

		if($page != '') {
            $return .= '/'.$page;
        }

		if(count($params)) {
			$return .= '/'.implode('/', $params);
		}

		return $return;

	}

}

?>
