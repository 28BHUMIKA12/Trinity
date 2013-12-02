<?php

/**
 * AjaxController class.
 * 
 * @extends Core_Controller
 */
class Admin_AjaxController extends Core_Admin{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){
		
		$this->_helper->layout()->disableLayout();
			
		parent::init();
		
	}
	
	
	/**
	 * getItemsForTagsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getItemsForTagsAction(){
	
		$p = $this->params;						
		// find user
		$result = $this->item->doQuery("
			select 
				concat(id,',',name) as tag 
			from 
				mus_item
			where
				parent_id!='1'
			");
		
		
		$data = $result->fetchAll();
		

		/*
			{
			  "tags": [
			   		{
		      		"tag": "Russia"
		    		}
		    	]
		    }
	    */
	
		// this is JSON
		header('Content-Type: application/json');
		echo '{"tags":'.Zend_Json::encode($data).'}';		
		
		die;
		
	}

}