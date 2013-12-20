<?php
/*

PENDING
timer

*/
/**
 * User_QuizController class.
 * 
 * @extends Core_Controller
 */
class User_QuizController extends Core_Trainee{

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
	 * homeAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function homeAction(){
	
		// quiz hsa stoped because are you home page or might be coming on another page.
		$this->session->quiz_starts = false;
		
		// get old scores
		$this->view->score = $this->quiz_played->fetchAll("user_id='".$this->auth->id."'");
		$count = count($this->view->score);		
		$update['is_stopped'] = 0;
		
		// if played are less then 7
		if($count < Model_DbTable_Quiz::MAXQUESTIONPLAYLIMIT && $count > 0){
			$update['is_stopped'] = 1;
			//die;
		}
		
		// this will update only if user exists
		$this->quiz->doUpdate($update,"user_id='".$this->auth->id."'");
		
		// not check the status, you cannot use is_stopped from above, u need to check in db!!
		$quiz = $this->quiz->fetchRow("user_id='".$this->auth->id."' and is_stopped='1'");
		
		// if stopped at last time, show button to start from there
		if($quiz){
			$this->view->stopped = true;
			//die;
		}
		else{
			$this->view->stopped = false;
		}
		
	}
	
	/**
	 * startNewQuizAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function startNewQuizAction(){

		// quiz started
		$this->session->quiz_starts = true;
		
		// delete all old quiz records
		$this->quiz->delete("user_id='".$this->auth->id."'");		
		$this->quiz_played->delete("user_id='".$this->auth->id."'");		
		
		$time = time();
		// insert new record with time
		$insert['user_id'] 		= $this->auth->id;
		$insert['quiz_start'] 	= $time;
		$insert['quiz_end'] 	= $time;
		$insert['total_time_played'] 	= '';
		$insert['is_stopped'] 	= '0';
		$this->quiz->doCreate($insert);
		
		//redirect to play mode
		$this->_redirect('user/quiz/play');
	}
	
	/**
	 * revisitLastStopAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function revisitLastStopAction(){
		
		// check if left in the middle last time.
		$check = $this->quiz->fetchRow("user_id='".$this->auth->id."' and is_stopped='1'");
		
		// check to ensure and redirect
		if($check){
			$this->_redirect("user/quiz/play");
		}
		// redirect anyway
		$this->_redirect("user/quiz/play");

	}
	
	/**
	 * playAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function playAction(){
		
		// check that state
		$check = $this->quiz->fetchRow("user_id='".$this->auth->id."'");
		$this->view->q = '';
		// must exist a new or stopped quiz at this point
		if($check){
			// private
			
			$this->view->q = $this->_loadQuestion($check);
			// check if q exists, else quiz over
			if($this->view->q){
				$this->view->type 	= $this->view->q->data_type;
				$this->view->a 		= $this->answer->fetchAll("question_id='".$this->view->q->id."'");
				$this->view->correct = $this->answer->fetchRow("is_correct='1' and question_id='".$this->view->q->id."'");
				$this->render();
					
			}
			else{
				// quiz over
				$this->_redirect('user/quiz/quiz-over');
				return;
			}
			
		}
		else{
			// something wrong send back to page
			$this->_redirect("user/quiz/home");
		}
	}
	
	/**
	 * _loadQuestion function.
	 * 
	 * @access private
	 * @param mixed $check
	 * @return void
	 */
	private function _loadQuestion($check){
		
		// if stoppped before
		if($check->is_stopped == 1){
			
			// set udpates
			$update['is_stopped'] = 0;
			$update['stopped_time'] = '';
			$this->quiz->doUpdate($update,"id='".$check->id."'");
			
		}
	
		//check question played
		$Q = $this->question->fetchRow(
			$this->question->select()
			->where("id not in (select question_id from mus_quiz_played where user_id='".$this->auth->id."') ")
			->where("is_active='1'")
			->order('RAND()')
		);
		
		// return Q;
		return $Q;
	}
	
	/**
	 * submitAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function submitAction(){
	
		$p = $this->params;
		
		// get question details
		$this->q = $this->question->doRead($p['question_id']);
		
		if(!$this->q){
			$this->render('404error');
			return;
		}
		
		
		$insertPlayed['is_correct'] = 0;
		
		// if answer submitted
		if($this->getRequest()->isPost()){
			
			// check the answer type and switch to appropriate submut query
			switch($p['answer_type']){
				
				case Model_DbTable_Answer::TEXT:
					// get the answer for this
					$check = $this->answer->fetchRow("question_id='".$p['question_id']."'");
					// you must have the answer record
					if($check){
						if(strtolower($check->data) == strtolower($p['answer'])){
							$AnswerSubmitFlag = 1;
						}
					}
					// if not answer,something wrong with DB
					else{
						$this->render('404error');
						return;
					}
					break;
				
				case Model_DbTable_Answer::MULTICHECK:
					// get the answer for this
					$check = $this->answer->fetchRow("question_id='".$p['question_id']."' and is_correct='1'");
					// you must have the answer record
					if($check){
						if(strtolower($check->id) == strtolower($p['answer'][0])){
							$AnswerSubmitFlag = 1;
						}
					}
					// if not answer,something wrong with DB
					else{
						$this->render('404error');
						return;
					}
					break;
				
				case Model_DbTable_Answer::IMAGESERIES:
					// get the answer for this
					$check = $this->answer->fetchRow("question_id='".$p['question_id']."' and is_correct='1'");
					
					// you must have the answer record
					// only for checkbox do this
					$p['answer'] = $p['answer'][0];
					
					if($check){
						if(strtolower($check->id) == strtolower($p['answer'])){
							$AnswerSubmitFlag = 1;
						}
					}
					// if not answer,something wrong with DB
					else{
						$this->render('404error');
						return;
					}
					break;
				
				case Model_DbTable_Answer::VIDEOSERIES:
					// get the answer for this
					$check = $this->answer->fetchRow("question_id='".$p['question_id']."' and is_correct='1'");
					// you must have the answer record
					
					if($check){
						if(strtolower($check->id) == strtolower($p['answer'])){
							$AnswerSubmitFlag = 1;
						}
					}
					// if not answer,something wrong with DB
					else{
						$this->render('404error');
						return;
					}
					break;
				
				case Model_DbTable_Answer::TRUEFALSE:
					// get the answer for this
					$check = $this->answer->fetchRow("question_id='".$p['question_id']."'");
					// you must have the answer record
					if($check){
						if(strtolower($check->is_correct) == strtolower($p['answer'])){
							$AnswerSubmitFlag = 1;
						}
					}
					// if not answer,something wrong with DB
					else{
						$this->render('404error');
						return;
					}
					break;
					
				default:	
			}
			
			// insert answer already submitted to db
			$insertPlayed['question_id'] 	= $this->q->id;
			$insertPlayed['is_correct'] 	= $AnswerSubmitFlag;
			$insertPlayed['user_id'] 		= $this->auth->id;
			$insertPlayed['answer_given'] 	= $p['answer'];
			$insertPlayed['dated'] 			= time();
			
			//print_r($p);
			//die;
			
			$this->quiz_played->doCreate($insertPlayed);
			
			// check how many questin has bee played
			$total_played = $this->quiz_played->fetchAll("user_id='".$this->auth->id."'");
			
			// check if total quiz played are to max limit
			if(count($total_played) >= Model_DbTable_Quiz::MAXQUESTIONPLAYLIMIT){
				// redirect, dont render
				$this->_redirect('/user/quiz/quiz-over');
				return;
			}
			
			// update user 
			$this->user->doUpdate($update,"id='".$user->id."'");
			
			$this->_redirect('user/quiz/play');	
		}
		
	}
	
	/**
	 * quizOverAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function quizOverAction(){
	
	}
	
	/**
	 * getElapsedTimeAjaxAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getElapsedTimeAjaxAction(){
	
		$check = $this->quiz->fetchRow("user_id='".$this->auth->id."'");
		
		$total_time_played = $check->total_time_played;
		if(!$total_time_played)
			$total_time_played = 0;
		
		echo "<center class='btn disabled btn-mini'>Time Elapsed<h3>".date('i:s',$total_time_played)."</h3></center>";
		
		$updateQuiz['quiz_end'] = time();
		$updateQuiz['total_time_played'] = $total_time_played + 1;
		$this->quiz->doUpdate($updateQuiz,"user_id='".$this->auth->id."'");
		
		die;
		
	}
}