<?php

/**
 * SupportController class.
 * 
 * @extends Core_Controller
 */
class SupportController extends Core_Controller{

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
	 * contactUsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function contactUsAction(){
	
			
	}

	/**
	 * tncAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function tncAction(){
	
	}
	
	/**
	 * faqAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function faqAction(){
	
	}
	
	/**
	 * forgotPasswordAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function forgotPasswordAction(){
			
		$p = $this->params;
		
		// if posted
		if($this->getRequest()->isPost()){
			
			$user = $this->user->fetchRow("email='".$p['email']."'");
		
			if($user){
				
				$this->mail->forgotPassword($user);
				$this->view->msg = 'Reset link sent to your email.';
			}
			else{
				$this->view->err = 'Email does not exists.';
			}
		}
	}
	
	/**
	 * setpwdAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function setpwdAction(){
		
		$p = $this->params;
		
		// if posted
		if($this->getRequest()->isPost()){
			
			if(trim($p['passwd'])){
				if(trim($p['passwd']) != trim($p['c_passwd'])){
					$this->view->err = 'Confirm password does not match.';
				}
				else{
					
					$update['password'] = $p['passwd'];
					$user = $this->user->fetchRow("sha1(id)='".$p['code']."'");
					
					if($user){
						$this->user->doUpdate($update,"id='".$user->id."'");
						$this->view->msg = 'Password changed.';
					}
					else{
						$this->view->err = 'URL malfunction;';
					}
				}
			}
		}
		
	}
	
}