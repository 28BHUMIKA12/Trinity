<?php

/**
 * Admin_StatisticsController class.
 * 
 * @extends Zend_Controller_Action
 */
class Admin_StatisticsController extends Core_Admin{

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
	 * showAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function showAction(){
	
		$p = $this->params;
						
		$select = $this->users->select()->from($this->users,array("from_unixtime(dated,'%Y-%m-%d') as date","dated","count(*) as c"));
		$select->where("id!='1'");
		$select->where("parent_id!='1'");

		$select->order(array("from_unixtime(dated,'%Y-%m-%d') desc"));
		$select->group(array("from_unixtime(dated,'%Y-%m-%d')"));
		
		$results = $this->users->fetchAll($select);
		
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);
	}
	
	/**
	 * showBydateAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function showByDateAction(){
	
		$p = $this->params;
		$this->view->date = $p['date'];			
		$select = $this->users->select();
		
		$select->where("from_unixtime(dated,'%Y-%m-%d')='".$p['date']."'");
		$select->where("id!='1'");
		$select->where("parent_id!='1'");

		$select->order(array("id desc"));
		
		$results = $this->users->fetchAll($select);		
		$this->view->paginator = $this->makePagination($results);
	}
	
	
}