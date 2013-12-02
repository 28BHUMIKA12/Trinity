<?php

/**
 * IndexController class.
 * 
 * @extends Core_Controller
 */
class IndexController extends Core_Controller{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){
	
		parent::init(false);
		
	}
	
	/**
	 * indexAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function indexAction(){
		
		$this->_redirect('/login');
		
	}

	
}