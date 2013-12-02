<?php

/**
 * AjaxController class.
 * 
 * @extends Core_Controller
 */
class AjaxController extends Core_Controller{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){
		
		$this->_helper->layout()->disableLayout();
			
		parent::init(false);
		
	}
	
	/**
	 * getAllItemsByEatType function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getAllItemsByEatTypeAction(){
	
		$p = $this->params;
			
		// is by tag
		if(trim($p['t']) && trim($p['m'])){
			
			$results = $this->item->fetchAll(
				$this->item
					->select()
					->where("id!='1' and parent_id='1' and eat_type='{$p['t']}'")
					//->where("id in (select item_id from mus_meal_item where meal_id='".$p['m']."')")
					->order(array("name asc"))
			);	
		}
		
		// this is JSON
		header('Content-Type: application/json');
		echo Zend_Json::encode($results->toArray());		
		
		die;
	}
	
	/**
	 * getSubItemAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getSubItemAction(){
	
		$p = $this->params;
			
		// is by tag
		if(trim($p['item'])){
			$results = $this->item->fetchAll(
				$this->item
					->select()
					->where("
						parent_id = '".$p['item']."'
					")
					->order(array("name asc"))
			);	
		}
		
		// this is JSON
		header('Content-Type: application/json');
		echo Zend_Json::encode($results->toArray());		
		
		die;
		
	}

	/**
	 * getSubItemUnitsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getSubItemUnitsAction(){
	
		$p = $this->params;
			
		// is by tag
		if(trim($p['item'])){
			$results = $this->item_stats->fetchAll(
				$this->item_stats
					->select()
					->where("
						item_id = '".$p['item']."'
					")
					->order(array("weight_unit asc"))
					->group(array("weight_unit"))
			);	
		}
		
		// this is JSON
		header('Content-Type: application/json');
		echo Zend_Json::encode($results->toArray());		
		
		die;
		
	}

	/**
	 * getSubItemQtyAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getSubItemQtyAction(){
	
		$p = $this->params;
						
		// is by tag
		if(trim($p['item'])){
			$results = $this->item_stats->fetchAll(
				$this->item_stats
					->select()
					->where("
						item_id = '".$p['item']."' and
						is_custom=''
					")
					->order(array("weight asc"))
			);	
		}
		
		// this is JSON
		header('Content-Type: application/json');
		echo Zend_Json::encode($results->toArray());		
		
		die;
		
	}
	
	/**
	 * getSubItemQtyStatsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getSubItemQtyStatsAction(){
	
		$p = $this->params;
						
		// is by tag
		if(trim($p['item'])){
			$results = $this->item_stats->fetchRow(
				$this->item_stats
					->select()
					->where("
						id = '".$p['item']."'
					")
					->order(array("weight asc"))
			);	
		}
		
		// this is JSON
		header('Content-Type: application/json');
		echo Zend_Json::encode($results->toArray());		
		
		die;
		
	}
	
	/**
	 * getCustomQtyStatsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getCustomQtyStatsAction(){
	
		$p = $this->params;
						
		// is by tag
		if(trim($p['item'])){
			$results = $this->item_stats->fetchRow(
				$this->item_stats
					->select()
					->where("
						item_id = '".$p['item']."'
					")
					->order(array("weight asc"))
			);	
		}
		
		//print_r($results);

		$weight = $results->weight;
		
		$data['weight_unit']		= $results->weight_unit;
		$data['grams'] 		= ($results->grams/$weight) * $p['qty'];
		$data['calories'] 	= ($results->calories/$weight) * $p['qty'];
		$data['protein'] 	= ($results->protein/$weight) * $p['qty'];
		$data['fat'] 		= ($results->fat/$weight) * $p['qty'];
		$data['carbs'] 		= ($results->carbs/$weight) * $p['qty'];
		$data['fiber']		= ($results->fiber/$weight) * $p['qty'];
		
		// this is JSON
		header('Content-Type: application/json');
		echo Zend_Json::encode($data);		
		
		die;
		
	}
	
	/**
	 * setCurrentLocationAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function setCurrentLocationAction(){
		
		$p = $this->params;
		$this->session->lat  = $p['lat'];
		$this->session->long  = $p['long'];
		
		// this is JSON
		header('Content-Type: application/json');
		echo Zend_Json::encode(array());		
		
		die;

	}

	/**
	 * getItemsForTagsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getItemsForTagsAction(){
	
		$p = $this->params;						
		$results = $this->item->fetchall(
			$this->item
				->select()
				->where("
					parent!='1'
				")
				->order(array("name asc"))
		);	
		
		// this is JSON
		header('Content-Type: application/json');
		echo Zend_Json::encode($results->toArray());		
		
		die;
		
	}

}