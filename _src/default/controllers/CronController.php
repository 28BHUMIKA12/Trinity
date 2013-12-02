<?php
/**
 * CronController class.
 * 
 * @extends Zend_Controller_Action
 */
class CronController extends Zend_Controller_Action{
	
	/**
	 * runAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function runAction(){
	
		//$this->user_weight->setWeight($this->auth->id,time(),$update['weight'],$update['weight_lbs']);
		$date = Zend_Date::now();
		$day = $date->get(Zend_Date::DAY_SHORT);
		$month = $date->get(Zend_Date::MONTH);
		$year = $date->get(Zend_Date::YEAR);
		
		$Users = new Model_DbTable_User;
		$UserRequired = new Model_DbTable_User_Required;
		
		// not admin ofcourse
		$AllUsers = $Users->fetchAll("id!='1'");
		
		// for all users set todays weight vs cals
		foreach($AllUsers as $key=>$value){
		
			// get target cals
			$UserRequiredTargets = $UserRequired->getTargets($value->id);
			
			// get actual cals
			$actual_cals = $this->_getActualCals($value->id);
			
			// insert wait
			$this->_setWeight(
				$value->id,$day,$month,$year,
				$value->weight,
				$value->weight_lbs,$actual_cals,$UserRequiredTargets['cals']
				
			);
			
		    $mail = new Zend_Mail();
		    $mail->setBodyText("Trinity Cron worked 
		    
		    	{$value->id},{$day},{$month},{$year},
		    	{$value->weight},{$value->weight_lbs},
		    	{$actual_cals}
		    	
		     at. '".date("d-m.Y h:i:s",time()));
		    $mail->setFrom('narwalsandeep@gmail.com', 'trinity sandbox');
		    $mail->addTo('narwalsandeep@gmail.com', 'Me');
		    $mail->setSubject('trinity sandbox');
		    $mail->send();

		}	
		
		die;
			
	}
	
	/**
	 * _getActualCals function.
	 * 
	 * @access private
	 * @param mixed $id
	 * @return void
	 */
	private function _getActualCals($id){
	
		$UserMeal = new Model_DbTable_User_Meal;
		$UserMealItems = new Model_DbTable_User_Meal_Items;
		$UserItemStats = new Model_DbTable_Item_Stats;
		
		// get default meal items
		$defaultMeal = $UserMeal->fetchRow("name='Default' and user_id='{$id}'");
		
		$defaultItems = $UserMealItems->fetchAll("user_meal_id='{$defaultMeal->id}'");
		
		$cals = 0.0;
		// calculate cals for each item
		foreach($defaultItems as $key=>$value){
			$item = $UserItemStats->doRead($value->item_stats_id);
			$cals += $item->calories;
		}
		
		return $cals;
		
	}
	
	/**
	 * _setWeight function.
	 * 
	 * @access private
	 * @param mixed $id
	 * @param mixed $day
	 * @param mixed $month
	 * @param mixed $year
	 * @param mixed $weight
	 * @param mixed $weight_lbs
	 * @param mixed $actual_cals
	 * @param mixed $target_cals
	 * @return void
	 */
	private function _setWeight($id,$day,$month,$year,$weight,$weight_lbs,$actual_cals,$target_cals){
		 
		$UserWeight = new Model_DbTable_User_Weight;
			
		$checkSameRecord = $UserWeight->fetchRow("user_id='".$id."' and day='".$day."' and month='".$month."' and year='".$year."'");
		
		if($checkSameRecord){
			$UserWeight->doDelete($checkSameRecord->id);		
		}
		
		$insert['user_id'] 	 	= $id;
		$insert['weight']  		= $weight;
		$insert['weight_lbs']  	= $weight_lbs;
		$insert['day'] 			= $day;
		$insert['month'] 		= $month;
		$insert['year'] 		= $year;
		$insert['actual_cals'] 		= round($actual_cals);
		$insert['target_cals'] 		= round($target_cals);
		$insert['dated']  		= time();
		
		$UserWeight->doCreate($insert);
		
		//print_r($insert);
	
		//die;
	}
}

