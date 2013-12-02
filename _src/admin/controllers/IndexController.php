<?php

/**
 * Admin_IndexController class.
 * 
 * @extends Core_Controller
 */
class Admin_IndexController extends Core_Admin{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){
	
		parent::init();
		parent::layout('admin');
		
	}
	
	/**
	 * indexAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function indexAction(){
		$this->_redirect("admin/users/list");
	}
	
}