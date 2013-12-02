<?php

/**
 * Admin_ExerciseController class.
 * 
 * @extends Zend_Controller_Action
 */
class Admin_ExerciseController extends Core_Admin{

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
		$this->_redirect('/admin/exercise/list');
	}
	
	
	/**
	 * listAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function listAction(){
	
		$p = $this->params;
			
		$results = $this->exercise->fetchAll();
	
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);
		$this->view->title = 'Create Exercise';
		$this->view->formaction = HTTP.WWW_ROOT.'/admin/exercise/create';
		
		if(trim($p['id'])){
			$this->view->exercise = $this->exercise->doRead($p['id']);
			$this->view->title = 'Edit Exercise';
			$this->view->formaction = HTTP.WWW_ROOT.'/admin/exercise/edit/id/'.$this->view->exercise->id;
			$this->view->editMode = true;
		}
		
	}
	
	/**
	 * editAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function editAction(){
	
		$p = $this->params;
		
		if($this->getRequest()->isPost()){
			
			// only upload if provided
			if($_FILES['pic']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['pic'],DOC_ROOT.'/uploads/',$time)){
					$p['pic'] = $time.'_'.$_FILES['pic']['name'];
				}
			}
			
			// only upload if provided
			if($_FILES['video']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['video'],DOC_ROOT.'/uploads/',$time)){
					$p['video'] = $time.'_'.$_FILES['video']['name'];
				}
			}
			
			if($this->exercise->doUpdate($p,"id='".$p['id']."'")){	
				$this->_redirect("admin/exercise/list");
			}	
		}
		$this->_redirect("admin/exercise/list");
		
		
	}
	
	/**
	 * deleteImageAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteImageAction(){
		
		$p = $this->params;
		
		$u['pic'] = '';
		$this->exercise->doUpdate($u,"id='".$p['id']."'");
		
		$this->_redirect("admin/exercise/list");
	}
	
	/**
	 * createAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function createAction(){
	
		$p = $this->params;
		
		$p['dated'] = time();
		if($this->getRequest()->isPost()){
			
			// only upload if provided
			if($_FILES['pic']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['pic'],DOC_ROOT.'/uploads/',$time)){
					$p['pic'] = $time.'_'.$_FILES['pic']['name'];
				}
			}
			
			// only upload if provided
			if($_FILES['video']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['video'],DOC_ROOT.'/uploads/',$time)){
					$p['video'] = $time.'_'.$_FILES['video']['name'];
				}
			}
			
			if($this->exercise->doCreate($p)){	
				$this->_redirect("admin/exercise/list");
			}	
		}
		$this->_redirect("admin/exercise/list");
		
	}
	
	/**
	 * deleteConfirmAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteConfirmAction(){
		$p = $this->params;
		
		$this->view->exercise = $this->exercise->doRead($p['id']);
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
		
		$this->exercise->doDelete($p['id']);
		
		$this->_redirect("admin/exercise/list");
	}

	/**
	 * deleteConfirmDefaultAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteConfirmDefaultAction(){
		$p = $this->params;
		
		$this->view->exercise_default = $this->exercise_default->doRead($p['id']);
		$this->view->id = $p['id'];
	
	}
	
	
	/**
	 * deleteAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteDefaultAction(){
	
		$p = $this->params;
		
		$this->exercise_default->doDelete($p['id']);
		
		$this->_redirect("admin/exercise/default");
	}
	
	/**
	 * createDefaultAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function createDefaultAction(){
	
		$p = $this->params;
		
		$p['dated'] = time();
		
		// if posted
		if($this->getRequest()->isPost()){
			// insert each selecte exercise
			foreach($p['exercise_id'] as $key=>$value){
				$insert = $p;
				$insert['exercise_id'] = $value;
				$this->exercise_default->doCreate($insert);	
			}
		}
		$this->_redirect("admin/exercise/default");
		
	}

	/**
	 * defaultAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function defaultAction(){
	
		$p = $this->params;
			
		$select = $this->exercise_default->select();
		//$select = $select->group(array('weekday','body_type','goal','gender'));
		$select = $select->order(array('weekday asc','body_type asc','gender desc','goal asc'));
		$results = $this->exercise_default->fetchAll($select);
	
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);

	}
	
}