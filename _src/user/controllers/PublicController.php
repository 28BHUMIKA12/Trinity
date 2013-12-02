<?php

/**
 * User_PublicController class.
 * 
 * @extends Core_Controller
 */
class User_PublicController extends Core_Trainee{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){
		//die;
		parent::init(false);
	}
	
	/**
	 * trainerRequestAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function trainerRequestAction(){
	
		$p = $this->params;
		$this->view->code = $this->trainer_trainee->fetchRow("sha1(id)='".$p['code']."'");	
		
		$this->view->trainer  = $this->user->doRead($this->view->code->trainer_id);

		if(!Zend_Auth::getInstance()->hasIdentity()){
			$this->session->redirectTo = '/user/public/trainer-request/code/'.$p['code'];
			$this->_redirect("/login");
		}
		if(!$this->view->code)
			$this->view->err = 'An Error occurred. URL malfunction.';
		
	}
	
	
}