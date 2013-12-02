<?php

/**
 * VisitorController class.
 * 
 * @extends Core_Controller
 */
class VisitorController extends Core_Controller{

	
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
		//	$this->_helper->layout()->disableLayout();
	}
	
	/**
	 * registerAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function registerAction(){
		
		$p = $this->params;
		
		if($this->getRequest()->isPost()){
			
			$checkEmail = $this->user->fetchrow("email='".$p['email']."'");
			
			if($checkEmail){
				$this->view->err .= "Email must be unique.<br>";
				$flag = true;
			}
			if(!$p['password']){
				$this->view->err .= "Password is required.<br>";
				$flag = true;
			}
			if($p['password'] != $p['c_password']){
				$this->view->err .= "Confirm Password does not match.<br>";
				$flag = true;
			}
			if(!$p['tnc']){
				$this->view->err .= "You must accept Terms and Conditions.<br>";
				$flag = true;
			}
			if(!$flag){			
			
				// only upload if provided
				if($_FILES['image']['tmp_name']){
					// time to use as unique
					$time = time();
					if(Model_Custom_File::upload($_FILES['image'],DOC_ROOT.'/uploads/',$time)){
						$p['pic'] = $time.'_'.$_FILES['image']['name'];
					}
				}
				// else upload default pic
				else{
					$p['pic'] = 'icon-user.png';
				}
				
				// set weight in kg
				$p['weight'] = $p['weight_lbs']/2.2;
				// set height in cms
				$p['height'] = round((($p['height_feet']*12) + $p['height_inches']) * 2.54);
				
				$p['user_type'] = Model_DbTable_User::TRAINEE;
				
				if($LastId = $this->user->doCreate($p)){	
					// set weight
					//$this->user_weight->setWeight($LastId,time(),$p['weight'],$p['weight_lbs']);
					// initiate required 
					$this->user_required->initiate($LastId);
					// set default meal
					$this->user_meal->initiate($LastId);
					// get default exercise
					$this->user_exercise->initiate($LastId);
								
					$this->_redirect("visitor/thank-you");
				}	
				else{
					$this->view->err = "An error occurred, please try again.";
				}
			}
		}
		
		$this->view->user = json_decode(json_encode($p));
		//print_r($this->view->user);
				
	}
	
	/**
	 * confirmAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function confirmAction(){
		
		$this->usersData = $this->user->fetchRow("sha1(u_email_address)='".$this->params['id']."'");
		
		// confirm and change status
		if(count($this->usersData)){
			$data['u_status'] = Model_DbTable_User::CONFIRMED;
			$data['u_id'] = $this->usersData->u_id;
			$this->user->doUpdate($data,"u_id='".$this->usersData->u_id."'");
			$this->view->status=true; 			
		}
		else{	
			$this->view->status=false; 			
		}
	}
	
	/**
	 * thankYouAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function thankYouAction(){
	
	}

}