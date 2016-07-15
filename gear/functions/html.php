<?php
		
	function html_convertAttribute($attributes) {
		
		$return = '';
			
		foreach($attributes as $key => $val) {
				
			if(is_int($key)) {
					
				$return .= ' '.$val;
					
			} else {
					
				if(is_array($val)) {
					$val = implode(' ', $val);	
				}
				
				$return .= ' '.htmlspecialchars($key).'="'.htmlspecialchars($val).'"';	
				
			}			
			
		}
			
		return $return;
		
	}
	
	function generateRandomString($length = 10) {
    
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	
	}
	
	function getDeleteModal($title, $content) {
	
		if($content == '') {
			$content = lang::get('really_delete');	
		}
		
		if($title == '') {
			$title = lang::get('really_delete');	
		}
		
	?>
	<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title"><?= $title ?></h3>
				</div>
				<div class="modal-body"><?= $content; ?></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?= lang::get('close') ?></button>
					<button type="button" class="btn btn-sm btn-danger confirm"><?= lang::get('delete') ?></button>
				</div>
			</div>
		</div>
	</div>
	<?php
	}

?>