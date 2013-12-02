<?php

/**
 * User_AccountController class.
 * 
 * @extends Core_Controller
 */
class User_AccountController extends Core_Trainee{

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
			
			$p['weight'] = $p['weight_lbs']/2.2;
			$p['height'] = ((($p['height_feet']*12) + $p['height_inches']) * 2.54);
			
			// update
			$this->user->doUpdate($p,"id='".$this->auth->id."'");
			
			// set weight
			//$this->user_weight->setWeight($this->auth->id,time(),$p['weight'],$p['weight_lbs']);
			
			// required updated
			$this->user_required->delete("user_id='".$this->auth->id."'");
			$this->user_required->initiate($this->auth->id);
			
			// required updated
			if($p['update_my_meal']){
				$this->user_meal->delete("name='Default' and user_id='".$this->auth->id."'");
				//$this->user_meal->delete("name='Plan 1' and user_id='".$this->auth->id."'");
				//$this->user_meal->delete("name='Plan 2' and user_id='".$this->auth->id."'");
				$this->user_meal->initiate($this->auth->id,true);
			}
						
			$this->_redirect('user/meal/plan');
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
	
	

	/**
	 * acceptTrainerAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function acceptTrainerAction(){
		
		$p = $this->params;
		$this->view->code = $this->trainer_trainee->fetchRow("sha1(id)='".$p['code']."'");	
		
		$this->view->trainer  = $this->user->doRead($this->view->code->trainer_id);

		if(!$this->view->code){
			$this->view->err = 'An Error occurred. Trainer does not exists or URL malfunction.';
		}
		else{
			$update['status'] = Model_DbTable_Trainer_Trainee::ACCEPTED;
			$this->trainer_trainee->doUpdate($update,"id={$this->view->code->id}");
			
		}		
	}
}