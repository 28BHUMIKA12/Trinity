<?php

/**
 * Trainer_AccountController class.
 * 
 * @extends Core_Controller
 */
class Trainer_AccountController extends Core_Trainer{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){
	
		parent::init();
	}
	

	/**
	 * profileAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function profileAction(){
		
		$p = $this->params;
			
		// if posted
		if($this->getRequest()->isPost()){
			
			// only upload if provided
			if($_FILES['image']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['image'],DOC_ROOT.'/uploads/',$time)){
					$p['pic'] = $time.'_'.$_FILES['image']['name'];
				}
			}
				
			$this->user->doUpdate($p,"id='".$this->auth->id."'");
			
			//$this->_redirect('user/meal/plan');
		}
		
		$user = $this->user->doRead($this->auth->id);
		$this->view->user = $user;
		
	}	
	
	/**
	 * changePasswordAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function changePasswordAction(){
		
		$p = $this->params;
		
		// if posted
		if($this->getRequest()->isPost()){
			
			if($p['password'] != $p['c_password']){
			
				$err = true;
				$this->view->err = 'Confirm password does not match.';
			}
			
			if(!$err){
				$this->user->doUpdate($p,"id='".$this->auth->id."'");
				$this->view->msg = 'Password change successfully.';
			}
		}
		
	}
	
}