<?php

/**
 * Model_DbTable_User_Meal_Items class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_User_Meal_Items extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_user_meal_items')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_user_meal_items';

	/**
	 * _primary
	 * 
	 * (default value: 'id')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_primary = 'id';
	
	/**
	 * _dependentTables
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_dependentTables = array(
	);
	
	
	/**
	 * initiate function.
	 * 
	 * @access public
	 * @return void
	 */
	public function initiate($id){
		
		$Meal_Items = new Model_DbTable_Meal_Items;
		$Meal_Itemss = $Meal_Items->fetchAll();
		
		$item = new Model_DbTable_Item;
		$items = $item->fetchAll($item->select()->where("id!='1'")->limit(2));
		
		foreach($Meal_Itemss as $key=>$value){
			
			foreach($items as $key_i=>$value_i){
				
				$insert['code']			= time();
				$insert['name']			= 'Default';
				$insert['user_id'] 		= $id;
				$insert['Meal_Items_id'] 		= $value->id;
				$insert['item_id'] 		= $value_i->id;
				$insert['item_stats_id'] = 1;
				$insert['eat_type'] 	= Model_DbTable_ITEM::EAT_IN;
				$this->doCreate($insert);
			}
		
		}
		
 	}
	
	
	/**
	 * initiateEmpty function.
	 * 
	 * @access public
	 * @return void
	 */
	public function initiateEmpty($id){
		
		$Meal_Items = new Model_DbTable_Meal_Items;
		$Meal_Itemss = $Meal_Items->fetchAll();
		
		foreach($Meal_Itemss as $key=>$value){
			$insert['code']			= time();
			$insert['name']			= 'Default';
			$insert['user_id'] 		= $id;
			$insert['Meal_Items_id'] 		= $value->id;
			$insert['item_id'] 		= $value_i->id;
			$insert['item_stats_id'] = 1;
			$insert['eat_type'] 	= Model_DbTable_ITEM::EAT_IN;
			$this->doCreate($insert);
		}
		
 	}
	
}