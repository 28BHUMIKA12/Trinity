<?php

/**
 * User_MealController class.
 * 
 * @extends Core_Controller
 */
class User_MealController extends Core_Trainee{

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
	 * planAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function planAction(){
		
		$p = $this->params;
		
		// always load new exercise
		//$this->user_exercise->delete("user_id='".$this->auth->id."'");
		//$this->user_exercise->getDefault($this->auth->id);
		
		$this->view->meal_master = $this->meal->fetchAll("id!='7'");
		
		$this->view->after_meal = $p['after_meal'];
		
		$this->view->user_meal_plans = 
			$this->user_meal->fetchAll($this->user_meal->select()->where("user_id='".$this->auth->id."'")->order(array("name asc")));
		
		// if not meal name selected, use default
		if(!$p['code']){
			$this->view->user_meal_data = $this->user_meal->fetchRow("name='Default' and user_id='".$this->auth->id."'");
			$this->view->c = $this->view->user_meal_data->code;
			
		}
		else{
			$this->view->c = $p['code'];
	
			$this->view->user_meal_data = $this->user_meal->fetchRow(
				"code='".$p['code']."' and user_id='".$this->auth->id."'");					
			//echo "code='".$p['code']."'";
			
		}
		
		// if posted for edit or delete
		if($this->getRequest()->isPost()){
			
			if(isset($p['delete'])){
				$this->_redirect('user/meal/delete-confirm/code/'.$this->view->user_meal_data->code);
			}
			
		}
		
	}	

	/**
	 * printAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function printAction(){
		
		$this->_helper->layout()->disableLayout();
		$p = $this->params;
		
		$this->view->meal_master = $this->meal->fetchAll("id!='7'");
		
		$this->view->after_meal = $p['after_meal'];
		
		$this->view->user_meal_plans = 
			$this->user_meal->fetchAll($this->user_meal->select()->where("user_id='".$this->auth->id."'")->order(array("name asc")));
		
		// if not meal name selected, use default
		if(!$p['code']){
			$this->view->c = 'Default';
			$this->view->user_meal_data = $this->user_meal->fetchRow("name='Default' and user_id='".$this->auth->id."'");
		}
		else{
			$this->view->c = $p['code'];
	
			$this->view->user_meal_data = $this->user_meal->fetchRow(
				"code='".$p['code']."'");					
		}
		
		// if posted for edit or delete
		if($this->getRequest()->isPost()){
			
			if(isset($p['delete'])){
				$this->_redirect('user/meal/delete-confirm/code/'.$this->view->user_meal_data->code);
			}
			
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
		
		$id = $p['edit_plan'];
		
		$update['item_id'] = $p['edit_pop_sub_item_'.$id];
		$update['item_stats_id'] = $p['edit_pop_sub_item_qty_'.$id];
		$update['eat_type'] = $p['edit_pop_t_'.$id];
		
		// if custom
		if($update['item_stats_id'] == 'custom'){
			
			// check if custom qty already exists
			$custom = $p['custom_qty_'.$id];
			$result = $this->item_stats->fetchRow("item_id='".$update['item_id']."' and weight='".$custom."'");
			
			// if yes update normal
			if($result->id){
				$update['item_stats_id'] = $result->id;
				$this->user_meal_items->doUpdate($update,"id='{$id}'");		
			}
			// else create record and update
			else{
				$result = $this->item_stats->fetchRow("item_id='".$update['item_id']."'");
				
				$insert['item_id'] = $update['item_id'];
				$insert['weight'] = $custom;
				$insert['weight_unit'] = $result['weight_unit'];
				$insert['grams'] = ($result->grams/$result->weight) * $custom;
				$insert['calories'] = ($result->calories/$result->weight) * $custom;
				$insert['protein'] = ($result->protein/$result->weight) * $custom;
				$insert['fat'] = ($result->fat/$result->weight) * $custom;
				$insert['carbs'] = ($result->carbs/$result->weight) * $custom;
				$insert['fiber'] = ($result->fiber/$result->weight) * $custom;
				$insert['is_custom'] = 1;
				
				$update['item_stats_id'] = $this->item_stats->doCreate($insert);
				$this->user_meal_items->doUpdate($update,"id='{$id}'");						
				
			}
		}
		// else update normal
		else{
			$this->user_meal_items->doUpdate($update,"id='{$id}'");		
		}
		
		
		$this->_redirect('user/meal/plan');
	}
	
	/**
	 * addAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function addAction(){
		
		$p = $this->params;
		
		$id = $p['add_plan'];
		
		$insert['user_meal_id'] = $p['user_meal_id'];
		$insert['meal_id'] = $p['meal_id'];
		$insert['item_id'] = $p['add_pop_sub_item_'.$id];
		$insert['item_stats_id'] = $p['add_pop_sub_item_qty_'.$id];
		$insert['eat_type'] = $p['add_pop_t_'.$id];
		
		$this->user_meal_items->doCreate($insert);
		
		$this->_redirect('user/meal/plan');
	}
	
	/**
	 * progressChartAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function progressChartAction(){
		
		$p = $this->params;
		
		if($this->getRequest()->isPost()){
			$update['weight_lbs'] 	= $p['weight_lbs'];
			$update['weight'] 		= round($p['weight_lbs']/2.2);
				
			$this->user->doUpdate($update,"id='{$this->auth->id}'");
			
		}
		
		$this->view->targets = $this->user_required->getTargets($this->auth->id);
		$this->view->user = $this->user->doRead($this->auth->id);
		$this->view->user_weight_data = $this->user_weight->fetchAll(
			$this->user_weight->select()->where("user_id='{$this->auth->id}'")->order(array('id asc'))->limit(30)
		);
		
		//echo '<pre>';
		//print_r($this->view->user_weight_data);
		//die;
	
	}
	
	/**
	 * 7DaysOutAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function sevenDaysOutAction(){
	
		$p = $this->params;
		$this->view->{'d'.$p['d']} = 'active';	
		
		$this->view->d = $p['d'];
		
		// get current values
		if($this->view->d){	
			$this->view->data = $this->user->getSevenDaysOut($p['d'],$this->auth);
		}
				
		switch ($this->view->d){
		
			case 1:
				$this->view->content = "<ul><li>Train your VISUALLY weakest 2 bodyparts</li>
										<li>  2 exercises per bodypart (4 exercises total)</li>
										<li>  5 sets of 15 reps per exercise</li>
										<li>  Train heavy as you can with 1 minute rests in-between sets</li></ul>";
 				break;
			case 2:
				$this->view->content = "<ul><li>Train your visually second weakest 2 bodyparts</li>
										<li>  2 exercises per bodypart (4 exercises total)</li>
										<li>  5 sets of 15 reps per exercise</li>
										<li>  Train heavy as you can with 1 minute rests in-between sets</li></ul>";
 				break;
			case 3:
				$this->view->content = " <ul><li>Train your visually 2 strongest body parts</li>
										<li>  2 exercises per bodypart (4 exercises total)</li>
										<li>  5 sets of 15 reps per exercise</li>
										<li>  Train heavy as you can with 1 minute rests in-between sets</li></ul>";
 				break;
			case 4:
				$this->view->content = "<ul><li>Train your 2nd two visually weakest bodyparts</li>
										<li>  2 different exercises than day #2</li>
										<li>  5 sets of 15 with 45 seconds rest time in-between sets</li></ul>";
 				break;
			case 5:
				$this->view->content = "<ul><li>Train after meal 1</li>
										<li>  Train visually weakest 2 body parts</li>
										<li>  2 different exercises than day #1</li>
										<li>  5 sets of 15 reps per exercise with 45 seconds rest in-between sets</li>
										<hr>Carb Note :
										<li>Starchy carbs are best<li>
										 Half of this day’s carbs should be simple, quick digesting carbohydrates</li></ul>";
 				break;
			case 6:
				$this->view->content = "ABSOLUTELY NONE
										<hr> Water Note:
										<ul><li>  Taper water off throughout the day</li>
										<li>  Have 2 cups of “weightless” tea by traditional medicinals that night and/or take any natural diuretic that contains dandelion root</li>
										<hr>Carb Note:
										<li>  You should be consuming mostly complex carbohydrates this day</li></ul>";
 
 				break;
 			case 7:
				$this->view->content = "Water Note:
										<ul><li>  Only sip water as needed</li></ul>";

		}
		
			
	}
	
	/**
	 * step1Action function.
	 * 
	 * @access public
	 * @return void
	 */
	public function step1Action(){
	
		$p = $this->params;
		
		$name = $p['name'];
		if(!trim($p['name']))	
			$name = 'Untitled';
			
		$insert['code']			= time();
		$insert['name']			= $name;
		$insert['user_id'] 		= $this->auth->id;
		
		$insert['eat_type'] 	= Model_DbTable_Item::GROCERY;		
		$eat_grocery = $this->user_meal->doCreate($insert);
		
		if($p['auto_fill']){
			$this->user_meal->setDefaultMeal($this->auth->id,1,Model_DbTable_Item::GROCERY,$eat_grocery,Model_DbTable_User_Meal::NUM_ITEM_M_1);
			$this->user_meal->setDefaultMeal($this->auth->id,2,Model_DbTable_Item::GROCERY,$eat_grocery,Model_DbTable_User_Meal::NUM_ITEM_M_2);
			$this->user_meal->setDefaultMeal($this->auth->id,3,Model_DbTable_Item::GROCERY,$eat_grocery,Model_DbTable_User_Meal::NUM_ITEM_M_3);
			$this->user_meal->setDefaultMeal($this->auth->id,4,Model_DbTable_Item::GROCERY,$eat_grocery,Model_DbTable_User_Meal::NUM_ITEM_M_4);
			$this->user_meal->setDefaultMeal($this->auth->id,5,Model_DbTable_Item::GROCERY,$eat_grocery,Model_DbTable_User_Meal::NUM_ITEM_M_5);
			$this->user_meal->setDefaultMeal($this->auth->id,6,Model_DbTable_Item::GROCERY,$eat_grocery,Model_DbTable_User_Meal::NUM_ITEM_M_6);
			$this->user_meal->setDefaultMeal($this->auth->id,7,Model_DbTable_Item::GROCERY,$eat_grocery,Model_DbTable_User_Meal::NUM_ITEM_M_7);
		}
		
		$plan = $this->user_meal->doRead($eat_grocery);
		
		$this->_redirect('user/meal/plan/code/'.$plan->code);
		
	}
	
	/**
	 * createAction function.
	 * 
	 * @access public
	 * @return void
	public function createAction(){
		
		$p = $this->params;
		
		$p['user_id'] = $this->auth->id;
		
		$this->view->meal_master = $this->meal->fetchAll();

		if(!$p['t'])
			$this->view->t = Model_DbTable_Item::GROCERY;
		
		// select all items for dropdown
		$this->view->data = $this->item->fetchAll("id!='1' and eat_type='".$this->view->t."' and parent_id='1'");
		
		// if not meal name selected, use default
		if(!$p['code']){
			$this->view->c = 'Default';
			$this->view->user_meal_data = $this->user_meal->fetchRow(
				"name='Default' and user_id='".$this->auth->id."' and eat_type='".$this->view->t."'");
		}
		else{
			$this->view->c = $p['code'];
	
			$this->view->user_meal_data = $this->user_meal->fetchRow(
				"code='".$p['code']."' and eat_type='".$this->view->t."'");	
						
		}
		
		// if posted 
		if($this->getRequest()->isPost()){
		
			$insert = array();
			$insert['user_meal_id']		= $p['user_meal_id'];
			$insert['meal_id']			= $p['meal_id'];
			$insert['item_id']			= $p['item_id']; 
			$insert['item_stats_id']	= $p['item_qty_id'];
			$insert['eat_type']			= $this->view->t;
			
			//print_r($p);
			$this->user_meal_items->doCreate($insert);
			//$this->_redirect('user/meal/plan');	
		}
		
	}
	*/
	
	/**
	 * deleteConfirmAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteConfirmAction(){
		$p = $this->params;
	
		$this->view->user_meal = $this->user_meal->fetchRow("code='".$p['code']."'");
		//$this->_redirect('user/meal/plan');
		
		// if found a meal.
		if($this->view->user_meal->name == 'Default'){
			$this->render("delete-confirm-cancelled");
		}
		
	}
	
	/**
	 * deleteAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteAction(){
		$p = $this->params;
	
		$this->view->user_meal = $this->user_meal->fetchRow("code='".$p['code']."'");
		
		// if found a meal.
		if($this->view->user_meal->name == 'Default'){
			$this->render("delete-confirm-cancelled");
		}
		
		$this->user_meal->delete("code='".$p['code']."'");
		
		$this->_redirect('user/meal/plan');
		
	}

	/**
	 * deleteItemAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteItemConfirmAction(){
		
		$p = $this->params;
		$this->view->id = $p['id'];
		
		$umi = $this->user_meal_items->doRead($p['id']);
		$this->view->item = $this->item->doRead($umi->item_id);

		//$um = $this->user_meal->doRead($umi->user_meal_id);
		//$this->_redirect('user/meal/plan/code/'.$um->code);
	}
	
	/**
	 * deleteItemAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteItemAction(){
		$p = $this->params;
		
		$umi = $this->user_meal_items->doRead($p['id']);

		$this->user_meal_items->doDelete($p['id']);
		
		$um = $this->user_meal->doRead($umi->user_meal_id);
		$this->_redirect('user/meal/plan/code/'.$um->code);
	}
	
	/**
	 * getLocationAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getLocationAction(){
	
		$p = $this->params;
		$this->view->id = $p['id'];
		
	}
	
	/**
	 * searchEatOutPlacesAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function searchEatOutPlacesAction(){
		$p = $this->params;
		
		$this->view->item = $this->user_meal_items->doRead($p['id']);
		
		$this->view->user_meal = $this->user_meal->doRead($this->view->item->user_meal_id);
		
		$this->view->item = $this->item->doRead($this->view->item->item_id);
		
		$this->view->user = $this->user->doRead($this->auth->id);
	}
	
	/**
	 * updateWaterIntakeAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function updateWaterIntakeAction(){
	
		$p = $this->params;
		
		if($this->getRequest()->isPost()){
			
			$update['water_intake'] = $p['water_intake'];
			
			$this->user->doUpdate($update,"id='{$this->auth->id}'");
		}
		
		$this->_redirect("user/meal/plan");
	}
	
	/**
	 * workoutAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function workoutAction(){
		
		$date = Zend_Date::now();
		$weekday = $date->get(Zend_Date::WEEKDAY_8601);
		 
		$this->view->data = $this->user_exercise->fetchAll("user_id='".$this->auth->id."' and weekday='".$weekday."'");
		
	}
	
	/**
	 * regenerateAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function regenerateAction(){
	
		$p = $this->params;
		
		$user_meal = $this->user_meal->fetchRow("code='".$p['re_code']."' and user_id='".$this->auth->id."'");
		
		if($user_meal){
			
			$this->user_meal_items->delete("user_meal_id='".$user_meal->id."'");
					
			$this->user_meal->setDefaultMeal($this->auth->id,1,Model_DbTable_Item::GROCERY,$user_meal->id,Model_DbTable_User_Meal::NUM_ITEM_M_1);
			$this->user_meal->setDefaultMeal($this->auth->id,2,Model_DbTable_Item::GROCERY,$user_meal->id,Model_DbTable_User_Meal::NUM_ITEM_M_2);
			$this->user_meal->setDefaultMeal($this->auth->id,3,Model_DbTable_Item::GROCERY,$user_meal->id,Model_DbTable_User_Meal::NUM_ITEM_M_3);
			$this->user_meal->setDefaultMeal($this->auth->id,4,Model_DbTable_Item::GROCERY,$user_meal->id,Model_DbTable_User_Meal::NUM_ITEM_M_4);
			$this->user_meal->setDefaultMeal($this->auth->id,5,Model_DbTable_Item::GROCERY,$user_meal->id,Model_DbTable_User_Meal::NUM_ITEM_M_5);
			$this->user_meal->setDefaultMeal($this->auth->id,6,Model_DbTable_Item::GROCERY,$user_meal->id,Model_DbTable_User_Meal::NUM_ITEM_M_6);
			$this->user_meal->setDefaultMeal($this->auth->id,7,Model_DbTable_Item::GROCERY,$user_meal->id,Model_DbTable_User_Meal::NUM_ITEM_M_7);
		
		}
		
		$this->_redirect('user/meal/plan/code/'.$p['re_code']);
		
		
	}
	
	
	/**
	 * exerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function exerciseAction(){
	
		$p = $this->params;
			
		$this->view->user = $this->user->doRead($this->auth->id);
		$select = $this->user_exercise->select();
		$select = $select->order(array('weekday asc'));
		$select = $select->where("user_id='".$this->auth->id."'");
		
		$results = $this->user_exercise->fetchAll($select);
	
		$this->view->total = count($results);
		$this->view->paginator = $this->makePagination($results);

	}
	
	
	/**
	 * deleteExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteExerciseAction(){
		
		$p = $this->params;
		
		$this->user_exercise->doDelete($p['id']);
		
		$this->_redirect("user/meal/exercise");
	}
	
	/**
	 * createExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function createExerciseAction(){
	
		$p = $this->params;
		
		$p['dated'] = time();
		
		// if posted
		if($this->getRequest()->isPost()){
			// insert each selecte exercise
			foreach($p['exercise_id'] as $key=>$value){

				$exercise_data = $this->exercise->doRead($value);
				$insert['user_id'] = $this->auth->id;
				$insert['weekday'] = $p['weekday'];
				$insert['pic'] = $exercise_data->pic;
				$insert['video'] = $exercise_data->video;
				$insert['title'] = $exercise_data->title;
				$insert['description'] = $exercise_data->description;
				$insert['dated'] = time();
				
				$this->user_exercise->doCreate($insert);
			}
		}
		$this->_redirect("user/meal/exercise");
		
	}
	
	/**
	 * createCustomExerciseAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function createCustomExerciseAction(){
	
		$p = $this->params;
		
		// if posted
		if($this->getRequest()->isPost()){
		
			// only upload if provided
			if($_FILES['pic']['tmp_name']){
				// time to use as unique
				$time = time();
				if(Model_Custom_File::upload($_FILES['pic'],DOC_ROOT.'/uploads/',$time)){
					$insert['pic'] = $time.'_'.$_FILES['pic']['name'];
				}
			}
			
			$insert['user_id'] = $this->auth->id;
			$insert['weekday'] = $p['weekday'];
			$insert['title'] = $p['title'];
			$insert['description'] = $p['description'];
			$insert['dated'] = time();
			
			$this->user_exercise->doCreate($insert);
		}
		$this->_redirect("user/meal/exercise");
		
	}

}