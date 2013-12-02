<?php

/**
 * Trainer_IndexController class.
 * 
 * @extends Core_Trainer
 */
class Trainer_IndexController extends Core_Trainer{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){
	
		parent::init();
		parent::layout('trainer');
		
	}
	
	/**
	 * indexAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function indexAction(){
		$this->_redirect("trainer/trainee/list");
	}
	
}