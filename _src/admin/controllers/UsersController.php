<?php

/**
 * Admin_UsersController class.
 * 
 * @extends Zend_Controller_Action
 */
class Admin_UsersController extends Core_Admin{

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
		$this->_redirect('/admin/users/list');
	}
	
	
	/**
	 * listAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function listAction(){
	
		$p = $this->params;
		
		$type = 'All';
		$this->view->type = 'All';
		if($p['type'])
			$this->view->type = $type = ucfirst($p['type']);
		
		// if posted
		if($this->getRequest()->isPost()){
			// if setting points
			if($p['set_points']){
				
				$update['points'] = $p['points'];
				$this->user->doUpdate($update,"id='".$p['trainee_id']."'");
			}			
		}
			
		if($this->params['search']){
			
			$results = $this->user->fetchAll(
				$this->users
					->select()
					->where($this->params['search_by']." LIKE ?","%".$this->params['search']."%")
					->where("id!='1'")
					->where("user_type='".$type."'")
					->order(array("id desc"))
			);
		}
		else{
						
			$select = $this->user->select();
			$select->where("id!='1'");
			
			if($type != 'All')
				$select->where("user_type='".$type."'");
			
			$select->order(array("id desc"));
			
			$results = $this->user->fetchAll($select);
		}
		
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);
	}
	
	/**
	 * createAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function createAction(){
	
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
				
				$p['user_type'] = Model_DbTable_User::TRAINER;
				
				if($LastId = $this->user->doCreate($p)){	
					$this->_redirect("admin/users/list/type/trainer");
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
	 * editTrainerAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function editTrainerAction(){
	
		$p = $this->params;
		
		if($this->getRequest()->isPost()){
			
			$checkEmail = $this->user->fetchrow("id!='".$p['id']."' and email='".$p['email']."'");
			
			// check email
			if($checkEmail){
				$this->view->err .= "Email already exists.<br>";
				$flag = true;
			}
			
			// if not error
			if(!$flag){			
			
				// only upload if provided
				if($_FILES['image']['tmp_name']){
					// time to use as unique
					$time = time();
					if(Model_Custom_File::upload($_FILES['image'],DOC_ROOT.'/uploads/',$time)){
						$p['pic'] = $time.'_'.$_FILES['image']['name'];
					}
				}
				
				// set weight in kg
				$p['weight'] = $p['weight_lbs']/2.2;
				// set height in cms
				$p['height'] = round((($p['height_feet']*12) + $p['height_inches']) * 2.54);
				
				// update user
				if($this->user->doUpdate($p,"id='".$p['id']."'")){	
					$this->_redirect("admin/users/list/type/trainer");
				}	
				else{
					$this->view->err = "An error occurred, please try again.";
				}
			}
		}
		
		$this->view->user = $this->user->doRead($p['id']);
		
		$this->view->trainee = $this->trainer_trainee->fetchAll(
			"trainer_id='".$p['id']."' and status='".Model_DbTable_Trainer_Trainee::ACCEPTED."'"
		);
		
		
	}
	
	/**
	 * editTraineeAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function editTraineeAction(){
	
		$p = $this->params;
		
		if($this->getRequest()->isPost()){
			
			$checkEmail = $this->user->fetchrow("id!='".$p['id']."' and email='".$p['email']."'");
			
			// check email
			if($checkEmail){
				$this->view->err .= "Email already exists.<br>";
				$flag = true;
			}
			
			// if not error
			if(!$flag){			
			
				// only upload if provided
				if($_FILES['image']['tmp_name']){
					// time to use as unique
					$time = time();
					if(Model_Custom_File::upload($_FILES['image'],DOC_ROOT.'/uploads/',$time)){
						$p['pic'] = $time.'_'.$_FILES['image']['name'];
					}
				}
				
				// set weight in kg
				$p['weight'] = $p['weight_lbs']/2.2;
				// set height in cms
				$p['height'] = round((($p['height_feet']*12) + $p['height_inches']) * 2.54);
				
				// update user
				if($this->user->doUpdate($p,"id='".$p['id']."'")){	
					$this->_redirect("admin/users/list/type/trainee");
				}	
				else{
					$this->view->err = "An error occurred, please try again.";
				}
			}
		}
		
		$this->view->user = $this->user->doRead($p['id']);
		
	}
	
	/**
	 * deleteConfirmAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteConfirmAction(){
		$p = $this->params;
		
		$this->view->user = $this->user->doRead($p['id']);
		$this->view->id = $p['id'];
	
	}
	
	
	/**
	 * deleteAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteAction(){
	
		$p = $this->params;
		
		$this->user->doDelete($p['id']);
		
		$this->_redirect("admin/users/list");
	}
	
	/**
	 * assignTraineeAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function assignTraineeAction(){
	
		$p = $this->params;
		
		$this->view->id = $p['id'];
		$trainer = $this->user->doRead($p['id']);				
				
		// if posted
		if($this->getRequest()->isPost()){
			$emails = explode(',',$p['emails']);
			
			foreach($emails as $key=>$value){
				$trainee = $this->user->fetchRow("email='{$value}' and user_type='".Model_DbTable_User::TRAINEE."'");
				if($trainee){
					$check = $this->trainer_trainee->fetchRow("trainer_id='{$trainer->id}' and trainee_id='{$trainee->id}'"); 
			
					// if not already assigned
					if(!$check){
						
						$insert['trainer_id'] = $trainer->id;
						$insert['trainee_id'] = $trainee->id;
						$insert['dated'] = time();
						$this->trainer_trainee->doCreate($insert);
						
					}
				
				}
					//$this->mail->inviteTrainees($trainer,$trainee);				
			}
			
			$this->view->msg = 'Request completed.';
		}
					
	}
	
	/**
	 * exerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function exerciseAction(){
	
	}
	
	/**
	 * defaultExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function defaultExerciseAction(){
	
	}
	
	/**
	 * deleteTraineeAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteTraineeAction(){
		
		$p = $this->params;
		
		$this->trainer_trainee->delete("trainer_id='".$p['id']."' and trainee_id='".$p['trainee_id']."'");
		$this->_redirect("admin/users/edit-trainer/id/".$p['id']);
		
	}
	
	/**
	 * editTraineeExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function editTraineeExerciseAction(){
	
		$p = $this->params;
			
		$this->view->user = $this->user->doRead($p['id']);
		$select = $this->user_exercise->select();
		$select = $select->order(array('weekday asc'));
		$select = $select->where("user_id='".$p['id']."'");
		
		$results = $this->user_exercise->fetchAll($select);
	
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);
		
	}
	
		
	/**
	 * createExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function createExerciseAction(){
	
		$p = $this->params;
		
		$p['dated'] = time();
		
		// if posted
		if($this->getRequest()->isPost()){
			// insert each selecte exercise
			foreach($p['exercise_id'] as $key=>$value){

				$exercise_data = $this->exercise->doRead($value);

				$insert['exercise_id'] = $value;
				$insert['user_id'] = $p['id'];
				$insert['weekday'] = $p['weekday'];
				$insert['pic'] = $exercise_data->pic;
				$insert['video'] = $exercise_data->video;
				$insert['title'] = $exercise_data->title;
				$insert['description'] = $exercise_data->description;
				$insert['dated'] = time();
				
				$this->user_exercise->doCreate($insert);
			}
		}
		$this->_redirect("admin/users/edit-trainee-exercise/id/".$p['id']);
		
	}
	
	/**
	 * createCustomExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function createCustomExerciseAction(){
	
		$p = $this->params;
		
		// if posted
		if($this->getRequest()->isPost()){
		
			// only upload if provided
			if($_FILES['pic']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['pic'],DOC_ROOT.'/uploads/',$time)){
					$insert['pic'] = $time.'_'.$_FILES['pic']['name'];
				}
			}
			
			$insert['user_id'] = $p['id'];
			$insert['weekday'] = $p['weekday'];
			$insert['title'] = $p['title'];
			$insert['description'] = $p['description'];
			$insert['dated'] = time();
			
			$this->user_exercise->doCreate($insert);
		}
		$this->_redirect("admin/users/edit-trainee-exercise/id/".$p['id']);
		
	}
	
	/**
	 * deleteExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteExerciseAction(){
		
		$p = $this->params;
		
		$this->user_exercise->doDelete($p['id']);
		
		$this->_redirect("admin/users/edit-trainee-exercise/id/".$p['trainee_id']);
	}


	/**
	 * quizScoreAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function quizScoreAction(){
		
		$p = $this->params;
		
		// get suer ans score
		$this->view->trainee = $this->user->doRead($p['id']);
		$this->view->score = $this->quiz_played->fetchAll("user_id='".$p['id']."'");
		
	}
	
}