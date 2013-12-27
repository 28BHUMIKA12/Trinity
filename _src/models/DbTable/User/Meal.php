<?php

/**
 * Model_DbTable_User_Meal class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_User_Meal extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_user_meal')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_user_meal';

	/**
	 * _primary
	 * 
	 * (default value: 'id')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_primary = 'id';
	
	/*
	* how many items to search to make a meal, Max food items to 200
	*/
	CONST NUM_ITEM_M_1 = 200;
	CONST NUM_ITEM_M_2 = 200;
	CONST NUM_ITEM_M_3 = 200;
	CONST NUM_ITEM_M_4 = 200;
	CONST NUM_ITEM_M_5 = 200;
	CONST NUM_ITEM_M_6 = 200;
	CONST NUM_ITEM_M_7 = 200;
	
	/*
		max and min calories that are allowed for each food item
	*/
	CONST MIN_CAL = 0.15;
	CONST MAX_CAL = 0.30;
	
	/*
		after meal : max and min calories that are allowed for each food item
	*/
	CONST AFTER_MEAL_MIN_CAL = 0.10;
	CONST AFTER_MEAL_MAX_CAL = 0.45;
	
	
	/**
	 * mealItemsMaster
	 * 
	 * @var mixed
	 * @access private
	 * @desc predifined meal items, for meal 1,2,3,4,5,6 and 7 , used to create meal with calories in it
	 */
	private $mealItemsMaster= array(
		
		1=>array(1783=>.25,1807=>.125,1808=>.25,1793=>0.25,1825=>.035,1827=>.05),
		2=>array(1816=>.125,1864=>.25,1833=>.5,1859=>.125),
		3=>array(1847=>.25,1823=>.05,1803=>.25,1866=>.025,1843=>0.125,1784=>0.25),
		4=>array(1859=>0.4,1835=>0.4,1821=>0.05,1864=>0.2),
		5=>array(1864=>0.2,1859=>0.30,1817=>0.05,1818=>0.0875,1833=>0.4),
		6=>array(1803=>0.2,1825=>0.3),
		7=>array(1800=>0.25,1824=>0.125,1825=>0.15,1872=>0.26,1871=>0.2)
		
	);

	/**
	 * mealItemsRange
	 * 
	 * @var mixed
	 * @access private
	 */
	private $mealItemsRange= array(
		
	
	);
	
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
	public function initiate($id,$onlyDefault = false){
		
		// create default and then insert meals
		$insert['code']			= time();
		$insert['name']			= 'Default';
		$insert['user_id'] 		= $id;
		$insert['eat_type'] 	= Model_DbTable_Item::GROCERY;
		
		// NOTE : meal not created yet!
		$eat_grocery = $this->doCreate($insert);
		
		// each of this line will call function() setDefaultMeal() below 
		// see line 164
		// each of below line have 5 parameters
		// 2nd paramater is the meal number 1,2,3,4,5,6 or 7
		// not go to line 164
		$this->setDefaultMeal($id,1,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_1);		
		$this->setDefaultMeal($id,2,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_2);		
		$this->setDefaultMeal($id,3,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_3);		
		$this->setDefaultMeal($id,4,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_4);		
		$this->setDefaultMeal($id,5,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_5);		
		$this->setDefaultMeal($id,6,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_6);		
		$this->setDefaultMeal($id,7,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_7);		

		// only if creating a fresh user
		// this parameter comes from profile change, so that i wont change other meals
		if(!$onlyDefault){
			$insert['code']			= time()+1;
			$insert['name']			= 'Plan 1';
			$insert['user_id'] 		= $id;
			$insert['eat_type'] 	= Model_DbTable_Item::GROCERY;
			$eat_grocery = $this->doCreate($insert);
			$this->setDefaultMeal($id,1,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_1);		
			$this->setDefaultMeal($id,2,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_2);		
			$this->setDefaultMeal($id,3,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_3);		
			$this->setDefaultMeal($id,4,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_4);		
			$this->setDefaultMeal($id,5,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_5);		
			$this->setDefaultMeal($id,6,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_6);		
			$this->setDefaultMeal($id,7,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_7);		
	
			$insert['code']			= time()+2;
			$insert['name']			= 'Plan 2';
			$insert['user_id'] 		= $id;
			$insert['eat_type'] 	= Model_DbTable_Item::GROCERY;
			$eat_grocery = $this->doCreate($insert);
			$this->setDefaultMeal($id,1,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_1);		
			$this->setDefaultMeal($id,2,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_2);		
			$this->setDefaultMeal($id,3,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_3);		
			$this->setDefaultMeal($id,4,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_4);		
			$this->setDefaultMeal($id,5,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_5);		
			$this->setDefaultMeal($id,6,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_6);		
			$this->setDefaultMeal($id,7,Model_DbTable_Item::GROCERY,$eat_grocery,self::NUM_ITEM_M_7);		
			
		}	
 	}
	
	
	/**
	 * setDefaultMeal function.
	 * 
	 * @access public
	 * @return void
	 */
	public function setDefaultMeal($id,$meal_id,$eat_type,$user_meal_id,$num_items){
	
		// ignore below 7 lines
		$item = new Model_DbTable_Item;
		$item_stats = new Model_DbTable_Item_Stats;
		$UserMealItems = new Model_DbTable_User_Meal_Items;
		$UserRequired = new Model_DbTable_User_Required;
		$insert['meal_id']			= $meal_id;
		$insert['user_meal_id'] 	= $user_meal_id;
		$insert['eat_type'] 		= $eat_type;
		
		// get items to add, they are fixed, 
		// below will get the predefined items check line 63 above
		// $meal_id are 1,2,3,4,5,6,7
		$itemsToCheck = $this->mealItemsMaster[$meal_id];
		
		// get target for this meal
		// this will calculate total target for a day needed.
		// not need to worry for it, this is based on BMR
		$TotalDayTargets = $UserRequired->getTargets($id);
		
		// get target for specific meal i.e. 1,2,3,4,5,6,7
		// for this user
		$MealTargets = $UserRequired->getMealRequirement($id,$meal_id);
		
		// ignore this line
		$MealTargetCals = $MealTargets['cals'];
		
		// for meal 7 its different
		// below is onl for meal 7, ignre this
		if($meal_id == 7){
			$MealTargetCals = $TotalDayTargets['cals']*.18;		
			//die;
		}
		
		
		// ABOVE we have only calculated how much is needed for each day and for each items 1,2,3,4,5,6,7
		// now below we are starting for get the food item
		// we will loop for each items in above line #63
		foreach($itemsToCheck as $key=>$value){
			
			// get stats for weight 1
			$ItemData = $item_stats->fetchRow("item_id='".$key."' and weight='1'");
			
			// get qty required for this item
			// e.g.  ((.25 x 2)/4)
			$RequiredQty = (floor($value * $MealTargetCals))/ $ItemData->calories;
			
			// get 125 factor
			$_125Factor = ((ceil(($RequiredQty / .125))) * .125);
			
			// below 4 lines get the weight for .125 wight factor
			$select = $item_stats->select();
			$select->where("weight='".$_125Factor."'");
			$select->where("item_id='".$key."'");
			$items_stat = $item_stats->fetchRow($select);

			// ignore this
			$insert['item_id']			= $key;
		
			// if stat exist, then insert, else create and insert
			if($items_stat)	{									
				$insert['item_stats_id']	= $items_stat->id;
			}
			else{
				
				$insertStats['item_id'] 	= $key;
				$insertStats['weight'] 		= $_125Factor;
				$insertStats['weight_unit'] = $ItemData->weight_unit;
				$insertStats['grams'] 		= $ItemData->grams * $_125Factor;
				$insertStats['calories'] 	= $ItemData->calories * $_125Factor;
				$insertStats['protein'] = $ItemData->protein * $_125Factor;
				$insertStats['fat'] 	= $ItemData->fat * $_125Factor;
				$insertStats['carbs'] 	= $ItemData->carbs * $_125Factor;
				$insertStats['fiber'] 	= $ItemData->fiber * $_125Factor;
			
				$LastId = $item_stats->doCreate($insertStats);	 
				$insert['item_stats_id']	= $LastId;
			}
			
			$UserMealItems->doCreate($insert);		
			
		}

	}
	
	
	/**
	 * setDefaultMeal function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @param mixed $meal_id
	 * @param mixed $eat_type
	 * @return void
	public function setDefaultMeal2($id,$meal_id,$eat_type,$user_meal_id,$num_items){
	
		$item = new Model_DbTable_Item;
		$item_stats = new Model_DbTable_Item_Stats;
		$UserMealItems = new Model_DbTable_User_Meal_Items;
		$UserRequired = new Model_DbTable_User_Required;

		// find items for this meal 
		// also check exclusive items
		// do not include parent items
		$sql = 	"
			select 
				i.id
			from 
				mus_item i
			where
				i.id!='1' and parent_id!='1' and eat_type='{$eat_type}' and
				i.id in (select item_id from mus_meal_item where meal_id='".$meal_id."') and
				i.id not in (select x_id from mus_item_exclusive where item_id=i.id)
			order by 
				RAND() 
			limit 
				$num_items";
		
		// get few random items for the meal
		$items_in = $item->doQuery($sql);
					
		// get target for this meal
		$targets = $UserRequired->getMealRequirement($id,$meal_id);
		$targetsTotal = $UserRequired->getTargets($id);
		
		$CurentStat = 0.0;
		// for each items found loop
		foreach($items_in as $key=>$value){		
			
			$select = $item_stats->select();

			// do below if not after meal i.e. !=7 , dont get specific cals
			$select->where("calories/".$targets['cals']." < ".self::MAX_CAL." and calories/".$targets['cals']." > ".self::MIN_CAL);
				
			// calories cannot be empty
			$select->where("(calories is not null or calories!='')");
			$select->where("item_id='".$value['id']."'");
			$select->order("rand()");
						
			// get stat for this item, ie. cals etc
			$items_stat = $item_stats->fetchRow($select);
					
			// if stats found
			if($items_stat){
			
				//$total_cal += $items_stat->calories;
				$insert['item_stats_id']	= $items_stat->id;
				$insert['item_id']			= $value['id'];
				$insert['meal_id']			= $meal_id;
				$insert['user_meal_id'] 	= $user_meal_id;
				$insert['eat_type'] 		= $eat_type;
				
				$UserMealItems->doCreate($insert);			
			}
			
			$CurrentStat += $items_stat->calories;
			
			$per = ($CurrentStat/$targets['cals'])*100;
				
			// check the current %
			if($per > 100){
				$CurrentStat = 0.0;
				break;
			}
			
		}	
		
	}
	 */
	
}