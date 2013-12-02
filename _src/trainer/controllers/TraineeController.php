<?php

/**
 * Trainer_TraineeController class.
 * 
 * @extends Core_Trainer
 */
class Trainer_TraineeController extends Core_Trainer{

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
		
		// if firtness tip posted
		if($this->getRequest()->isPost()){
			
			// if setting tips
			if($p['set_tip']){
				foreach($p['tip'] as $key=>$value){
					$id = $key;
					$update['fitness_tip_by_trainer'] = $value;
				}
				$this->user->doUpdate($update,"id='".$id."'");
			}			
			
			// if setting points
			if($p['set_points']){
				
				$update['points'] = $p['points'];
				$this->user->doUpdate($update,"id='".$p['trainee_id']."'");
			}			
			
		}
		
		// if searching
		if($this->params['search']){
			
			$results = $this->user->fetchAll(
				$this->users
					->select()
					->where($this->params['search_by']." LIKE ?","%".$this->params['search']."%")
					->where("id!='1'")
					->where("user_type='".Model_DbTable_User::TRAINEE."'")
					->order(array("id desc"))
			);
		}
		else{
						
			$sql = "
				select *, u.id as trainee_id 
				from mus_user u, mus_trainer_trainee tt
				where
					u.id = tt.trainee_id and
					tt.status='".Model_DbTable_Trainer_Trainee::ACCEPTED."' and
					tt.trainer_id = {$this->auth->id}
				";		
			$query = $this->trainer_trainee->doQuery($sql);
			$results = $query->fetchAll();
			
		}
		
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);
	}
	
	/**
	 * removeAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function removeAction(){
	
		$p = $this->params;
		
		$this->trainer_trainee->doDelete($p['id']);
		
		$this->_redirect("/trainer/trainee/list");
	}
	
	/**
	 * assignAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function assignAction(){
	
		$p = $this->params;
				
		// if posted
		if($this->getRequest()->isPost()){
			$emails = explode(',',$p['emails']);
			
			// for each email
			foreach($emails as $key=>$value){
				$trainee = $this->user->fetchRow("email='{$value}' and user_type='".Model_DbTable_User::TRAINEE."'");
				if($trainee){
					$check = $this->trainer_trainee->fetchRow("trainer_id='{$this->auth->id}' and trainee_id='{$trainee->id}'"); 
			
					// if not already assigned
					if(!$check){
						
						$insert['trainer_id'] = $this->auth->id;
						$insert['trainee_id'] = $trainee->id;
						$insert['dated'] = time();
						$insert['status'] = Model_DbTable_Trainer_Trainee::PENDING;
						
						$LastId = $this->trainer_trainee->doCreate($insert);
						
						$this->mail->inviteTrainees($this->auth,$trainee,true,$LastId);				
						$this->view->msg .= "Request completed for {$value}.<br>";	
					}
				}
				else{
					$this->view->msg .= "$value does not exists.<br>";
					$this->mail->inviteTrainees($this->auth,$value,false,NULL);				
				}
			}
			
		}
					
	}
	
	
	/**
	 * exerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function exerciseAction(){
	
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
	 * deleteExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteExerciseAction(){
		
		$p = $this->params;
		
		$this->user_exercise->doDelete($p['id']);
		
		$this->_redirect("trainer/trainee/exercise/id/".$p['trainee_id']);
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
		$this->_redirect("trainer/trainee/exercise/id/".$p['id']);
		
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
		$this->_redirect("trainer/trainee/exercise/id/".$p['id']);
		
	}

}