<?php

/**
 * Core_I class.
 * 
 * @extends Zend_Controller_Action
 */
class Core_I extends Zend_Controller_Action{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function initI($checkLogin){
		
		$this->auth = $this->view->auth = Zend_Auth::getInstance()->getStorage()->read();
		
		$this->p = $this->params = $this->getRequest()->getParams();

		if($checkLogin)
			if(!Zend_Auth::getInstance()->hasIdentity()){
				$this->_redirect('/login');
			}
			
		
		$this->user 		= $this->view->user 			= new Model_DbTable_User;
		$this->user_meal 	= $this->view->user_meal 	= new Model_DbTable_User_Meal;

		$this->user_meal_items 	= $this->view->user_meal_items 	= new Model_DbTable_User_Meal_Items;
		$this->user_exercise 	= $this->view->user_exercise 	= new Model_DbTable_User_Exercise;
		$this->user_required 	= $this->view->user_required 	= new Model_DbTable_User_Required;
		$this->user_weight 		= $this->view->user_weight 	= new Model_DbTable_User_Weight;

		
		$this->meal 		= $this->view->meal 		= new Model_DbTable_Meal;
		$this->item 		= $this->view->item 		= new Model_DbTable_Item;
		$this->meal_item  	= new Model_DbTable_Meal_Item;
		$this->goal 		= $this->view->goal 		= new Model_DbTable_Goal;
		
		$this->body_type 		= $this->view->body_type 			= new Model_DbTable_Body_Type;
		$this->item_stats 		= $this->view->item_stats 		= new Model_DbTable_Item_Stats;
		$this->item_exclusive 	= $this->view->item_exclusive 		= new Model_DbTable_Item_Exclusive;
		
		$this->exercise 		= $this->view->exercise 			= new Model_DbTable_Exercise;
		$this->exercise_default = $this->view->exercise_default 	= new Model_DbTable_Exercise_Default;
	
		$this->trainer_trainee = $this->view->trainer_trainee 			= new Model_DbTable_Trainer_Trainee;

		$this->question 	= new Model_DbTable_Question;
		$this->answer 		= new Model_DbTable_Answer;
		$this->quiz 		= new Model_DbTable_Quiz;
		$this->quiz_played 	= new Model_DbTable_Quiz_Played;
		
		$this->mail 		= $this->view->mail = new Model_Custom_Mail;
		$this->session 		= $this->view->session = new Zend_Session_Namespace;
		
	}
	
	/**
	 * setLayout function.
	 * 
	 * @access public
	 * @param mixed $layout
	 * @return void
	 */
	public function layout($layout){
		$this->_helper->layout()->setLayout($layout);

	}
	
	/**
	 * makePagination function.
	 * 
	 * @access public
	 * @param mixed $results
	 * @return $paginator
	 */
	public function makePagination($results){
	
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'search/pagination.phtml' 
            //Take note of this, we will be creating this file
        );

		$paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
       
        /**
         * We will be using $this->view->paginator to loop thru in our view ;-)
         */
        return $paginator;


	}
}
