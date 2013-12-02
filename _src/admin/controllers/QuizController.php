<?php

/**
 * Admin_QuizController class.
 * 
 * @extends Core_Controller
 */
class Admin_QuizController extends Core_Admin{

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
		$this->_redirect("admin/quiz/list");
	}
	
	/**
	 * listAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function listAction(){
		
		// get quiz and paginate it
		$results = $this->question->fetchAll();
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);

	}
	
	/**
	 * addAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function addAction(){
	
		// get all params
		$p = $this->params;
		
		// if posted
		if($this->getRequest()->isPost()){
			
			// only upload if provided
			if($_FILES['image']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['image'],DOC_ROOT.'/uploads/',$time)){
					$insert['image'] = $time.'_'.$_FILES['image']['name'];
				}
			}
			
			// only upload if provided
			if($_FILES['audio']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['audio'],DOC_ROOT.'/uploads/',$time)){
					$insert['audio'] = $time.'_'.$_FILES['audio']['name'];
				}
			}
			
			
			// set other params
			$insert['title'] = $p['title'];
			$insert['description'] = $p['description'];
			$insert['video'] = $p['video'];
			// create and get id
			$LastId = $this->question->doCreate($insert);
			
			// send to edit page and ask for answer
			$this->_redirect('admin/quiz/edit/id/'.$LastId);
			
		}
	}
	
	/**
	 * editAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function editAction(){
	
		$this->view->id = $this->params['id'];
		$p = $this->params;
		
		$this->view->step = $this->params['step'];
		$this->view->answer_type = $this->params['answer_type'];
		$this->view->qty = $this->params['qty'];	
		
		// get question details
		$this->view->question = $this->question->doRead($this->view->id);
		
		// check if answers already exists, show them
		$this->_checkExistingAnswers($p);

		// if posted answer
		if($this->getRequest()->isPost()){

			// if updating question
			if($p['q_update']){
				
				// only upload if provided
				if($_FILES['image']['tmp_name']){
					// time to use as unique
					$time = time();
					if(Model_Custom_File::upload($_FILES['image'],DOC_ROOT.'/uploads/',$time)){
						$insert['image'] = $time.'_'.$_FILES['image']['name'];
					}
				}
				
				// set other params
				$insert['title'] = $p['title'];
				$insert['description'] = $p['description'];
				$insert['video'] = $p['video'];
				// create and get id
				$this->question->doUpdate($insert,"id='{$this->view->id}'");
				
				// send to edit page and ask for answer
				$this->_redirect('admin/quiz/edit/id/'.$this->view->id);
				
			}
			
			// if updating answers
			// check for each answer type submission
			if($p['q_answer']){
				// private in scope
				$this->_setAnswers($p);
				
				$this->_redirect("admin/quiz/list");
			}
		}
	}
	
	/**
	 * _checkExistingAnswers function.
	 * 
	 * @access private
	 * @param mixed $p
	 * @return void
	 */
	private function _checkExistingAnswers($p){
	
		$checkType 	= $this->answer->fetchRow("question_id='".$this->view->id."'");
		
		// check if any answer added
		if($checkType){
		
			$this->view->type 		= $checkType->data_type;		
			$this->view->existingAnswers = $this->answer->fetchAll("question_id='".$this->view->id."'");
			$this->view->existingAnswers = $this->view->existingAnswers->toArray();
			// check type and display accordingly.
			
		}
		else{
			$this->view->no_answer = true;
			
		}
		

	}
	
	/**
	 * _setAnswers function.
	 * 
	 * @access private
	 * @param $p
	 * @return void
	 */
	private function _setAnswers($p){
		
		$flag 		= $p['switch_answer'];
		$question 	= $p['id'];
		
		// delete any previous answer
		$this->answer->delete("question_id='".$question."'");
		
		// set defaults
		$insert['data_type'] 	= $flag;
		$insert['question_id'] 	= $question;

		// update data type for question
		$udpateQ['data_type'] = $flag;
		$udpateQ['is_active'] = 1;	
		$this->question->doUpdate($udpateQ,"id='".$question."'");
	
		// switch to correct answer
		switch($flag){
			
			// add text answer
			case Model_DbTable_Answer::TEXT:
				$insert['data'] 		= $p['text_answer'];
				$insert['is_correct'] 	= 1;
				$this->answer->doCreate($insert);
				
				break;
				
			// add checkbox/multiple choices
			case Model_DbTable_Answer::MULTICHECK:
				
				// all checkbox in options
				$options = $p['options_answer'];
				$check_correct = $p['options_is_correct'];
				
				// insert each
				foreach($options as $key=>$value){
					
					$insert['data'] 		= $value;
					$insert['is_correct'] 	= 0;
					
					// if this correct
					if($check_correct[$key]){
						$insert['is_correct'] 	= 1;
					}
					$this->answer->doCreate($insert);
				}
									
				break;
		
			// add multiple images
			case Model_DbTable_Answer::IMAGESERIES:

				// all checkbox in options
				$check_correct = $p['options_is_correct'];
				
				// insert each
				foreach($_FILES as $key=>$value){
					
					$insert['data'] = '';
					$num = explode('_',$key);
					// only upload if provided
					if($value['tmp_name']){
						
						// time to use as unique
						$time = time();
						
						// if file cannot be uploaded, do not insert into db
						if(Model_Custom_File::upload($value,DOC_ROOT.'/uploads/',$time)){
							$insert['data'] = $time.'_'.$value['name'];
							$insert['is_correct'] 	= 0;
							
							// if this correct
							if($check_correct[$num[2]]){
								$insert['is_correct'] = 1;
							}
							$this->answer->doCreate($insert);	
						}
					}
				}
				break;

			// add video choices
			case Model_DbTable_Answer::VIDEOSERIES:
				
				// all checkbox in options
				$options = $p['video_answer'];
				$check_correct = $p['options_is_correct'];
				
				// insert each
				foreach($options as $key=>$value){
					
					$insert['data'] 		= $value;
					$insert['is_correct'] 	= 0;
					
					// if this correct
					if($check_correct[$key]){
						$insert['is_correct'] 	= 1;
					}
					$this->answer->doCreate($insert);
				}
									
				break;
					// add true false
			case Model_DbTable_Answer::TRUEFALSE:

				if($p['truefalse_answer'] == 'on'){
					$insert['data'] = true;
				}
				else{
					$insert['data'] = false;				
				}
				$insert['is_correct'] 	= 1;

				// insert 
				$this->answer->doCreate($insert);
				
				break;
		
			default:
		}
		
		$insert = array();

	}
	
	/**
	 * deleteConfirmAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteConfirmAction(){
	
		$this->view->question = $this->question->doRead($this->params['id']);
		
	}
	
	/**
	 * deleteAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteAction(){
		
		$this->question->doDelete($this->params['id']);
		$this->_redirect("admin/quiz/list");
	}
	
	/**
	 * deleteAllAnswersAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteAllAnswersAction(){
		$p = $this->params;
		
		$this->answer->delete("question_id = '".$p['id']."'");
		
		$this->question->doUpdate(array('data_type'=>'',"is_active"=>'0'),"id='".$p['id']."'");
		$this->_redirect('admin/quiz/edit/id/'.$p['id']);
	}
}