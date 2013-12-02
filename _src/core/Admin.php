<?php

/**
 * Core_Admin class.
 * 
 * @extends Core_I
 */
class Core_Admin extends Core_I{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init($checkLogin = true){
		
		$this->initI($checkLogin);
		
		// if admin send to admin module
		if($this->auth->id != '1'){
			$this->_redirect('/');
		}
		
		
	}
		
			
}
