<?php

/**
 * Model_DbTable_User_Required class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_User_Required extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_user_required')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_user_required';

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
	 * _meal_breakup
	 * 
	 * (default value: array('1'=>6,'2'=>6,'3'=>6,'4'=>6,'5'=>6,'6'=>6))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_meal_breakup = array('1'=>4.564,'2'=>7.532,'3'=>5.013,'4'=>7.532,'5'=>5.580,'6'=>7.532);
	
	/**
	 * _carbs_breakup
	 * 
	 * (default value: array('1'=>6,'2'=>6,'3'=>6,'4'=>6,'5'=>6,'6'=>6))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_carbs_breakup = array('1'=>3.333,'2'=>12.12,'3'=>4,'4'=>12.12,'5'=>5,'6'=>12.12);
	
	/**
	 * _carbs_breakup
	 * 
	 * (default value: array('1'=>6,'2'=>6,'3'=>6,'4'=>6,'5'=>6,'6'=>6))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_fiber_male_breakup = array('1'=>11,'2'=>4,'3'=>9,'4'=>4,'5'=>6,'6'=>4);
	
	/**
	 * _carbs_breakup
	 * 
	 * (default value: array('1'=>6,'2'=>6,'3'=>6,'4'=>6,'5'=>6,'6'=>6))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_fiber_female_breakup = array('1'=>8,'2'=>2,'3'=>6,'4'=>2,'5'=>5,'6'=>2);

	/**
	 * _training_per_week_breakup
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_training_per_week_breakup = array(
		'1'=>1,'2'=>1.1,'3'=>1.2,
		'4'=>1.3,'5'=>1.4,'6'=>1.5,
		'7'=>1.6,'8'=>1.7,'9'=>1.8,
		'10'=>1.9
	);
	
	
	/**
	 * _total
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_total;
	
	/**
	 * _protein
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_protein;
	
	/**
	 * _carbs
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_carbs;
	
	/**
	 * _fat
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_fat;
	
	/**
	 * _fiber
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_fiber;
	
	
	/**
	 * _user
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_user;
	
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
		
		$this->getTargets($id);
		
		$meal = new Model_DbTable_Meal;
		$data = $meal->fetchAll("id!='7'");
		
		foreach($data as $key=>$value){
			
			$insert['user_id'] = $id;
			$insert['meal_id'] = $value->id;
			
			$insert['cal'] 		= ceil( $this->_total / $this->_meal_breakup[$value->id] );
			$insert['protein'] 	= ceil( $this->_protein / 6 );
			$insert['fat'] 		= ceil( $this->_fat / 6 );
			$insert['carbs'] 	= ceil( $this->_carbs / $this->_carbs_breakup[$value->id] );

			if($this->_user->gender == 'Male'){
	 			$insert['fiber'] 	= $this->_fiber/$this->_fiber_male_breakup[$value->id];
			}
			
			if($this->_user->gender == 'Female'){
	 			$insert['fiber'] 	= $this->_fiber/$this->_fiber_female_breakup[$value->id];
			}
			
			$this->doCreate($insert);
			
		}
		
 	}
 	
 	
	/**
	 * getTarget function.
	 * 
	 * @access public
	 * @param mixed $param
	 * @param mixed $id
	 * @return void
	 */
	public function getTargets($id){
 		
 		$user = new Model_DbTable_User;
		$this->_user = $user->doRead($id);
		
 		if($this->_user->gender == 'Male'){
	 		$this->_fiber = '38';
		 	$this->_total =  ( (13.397 * $this->_user->weight) + (4.799 * $this->_user->height) - (5.677 * $this->_user->age) + 88.362 )  *  1.006;
		 	
		 }

	 	if($this->_user->gender == 'Female'){
	 		$this->_fiber = '25';
		 	$this->_total = ( (9.247 * $this->_user->weight) + (3.098 * $this->_user->height) - (4.330 * $this->_user->age) + 447.593 ) *  1.006;
		 }
		 
  		$this->_total = $this->_total * $this->_training_per_week_breakup[$this->_user->training_hrs_per_week];
		
		$bt = $this->_user->body_type;
 		
 		switch($bt){
 		
 			case 'Meso-Ecto';
 			
 				if($this->_user->goal == 'Fat Loss'){
 					$this->_total = $this->_total * 0.80;
 				} 
 				if($this->_user->goal == 'Muscle Gain'){
 					$this->_total = $this->_total * 1.17;
 				}
 				
 				if($this->_user->goal == 'Maintain & Tone'){
 					$this->_total = $this->_total * .985;
 				}
 				
 				$this->_carbs 	= ( 43 / 100 * $this->_total) / 4;
 				$this->_protein = ( 35 / 100 * $this->_total) / 4;
 				$this->_fat 	= ( 22 / 100 * $this->_total) / 9;
 				 
 				break;
 
 			case 'Ecto-Meso';
 
 				if($this->_user->goal == 'Fat Loss'){
 					$this->_total = $this->_total * 0.82;
 				}
 				if($this->_user->goal == 'Muscle Gain'){
 					$this->_total = $this->_total * 1.18;
 				}
 				
 				if($this->_user->goal == 'Maintain & Tone'){
 					$this->_total = $this->_total * .985;
 				}
 				$this->_carbs 	= ( 45 / 100 * $this->_total) / 4;
 				$this->_protein = ( 35 / 100 * $this->_total) / 4;
 				$this->_fat 	= ( 20 / 100 * $this->_total) / 9;
 			
 				break;
 
 			case 'Ectomorph';

 				if($this->_user->goal == 'Fat Loss'){
 					$this->_total = $this->_total * 0.85;
 				}
 				if($this->_user->goal == 'Muscle Gain'){
 					$this->_total = $this->_total * 1.2;
 				}
 				if($this->_user->goal == 'Maintain & Tone'){
 					$this->_total = $this->_total * .985;
 				}
 				
 				$this->_carbs 	= ( 50 / 100 * $this->_total) / 4;
 				$this->_protein = ( 35 / 100 * $this->_total) / 4;
 				$this->_fat 	= ( 15 / 100 * $this->_total) / 9;
 			
 				break;

 			case 'Mesomorph';

 				if($this->_user->goal == 'Fat Loss'){
 					$this->_total = $this->_total * 0.80;
 				}
 				if($this->_user->goal == 'Muscle Gain'){
 					$this->_total = $this->_total * 1.15;
 				}
 				if($this->_user->goal == 'Maintain & Tone'){
 					$this->_total = $this->_total * .975;
 				}
 				
 				$this->_carbs 	= ( 40 / 100 * $this->_total) / 4;
 				$this->_protein = ( 35 / 100 * $this->_total) / 4;
 				$this->_fat 	= ( 25 / 100 * $this->_total) / 9;
 			
 				break;

 			case 'Endo-Meso';

 				if($this->_user->goal == 'Fat Loss'){
 					$this->_total = $this->_total * 0.75;
 				}
 				if($this->_user->goal == 'Muscle Gain'){
 					$this->_total = $this->_total * 1.125;
 				}
 				if($this->_user->goal == 'Maintain & Tone'){
 					$this->_total = $this->_total * 0.9375;
 				}
 				
 				$this->_carbs 	= ( 35 / 100 * $this->_total) / 4;
 				$this->_protein = ( 45 / 100 * $this->_total) / 4;
 				$this->_fat 	= ( 20 / 100 * $this->_total) / 9;
 			
 				break;

 			case 'Endo';

 				if($this->_user->goal == 'Fat Loss'){
 					$this->_total = $this->_total * 0.70;
 				}
 				if($this->_user->goal == 'Muscle Gain'){
 					$this->_total = $this->_total * 1.1;
 				}
 				if($this->_user->goal == 'Maintain & Tone'){
 					$this->_total = $this->_total * .9;
 				}
 				
 				$this->_carbs 	= ( 30 / 100 * $this->_total) / 4;
 				$this->_protein = ( 50 / 100 * $this->_total) / 4;
 				$this->_fat 	= ( 20 / 100 * $this->_total) / 9;
 			
 				break;

 			case 'Meso-Endo';

 				if($this->_user->goal == 'Fat Loss'){
 					$this->_total = $this->_total * 0.78;
 				}
 				if($this->_user->goal == 'Muscle Gain'){
 					$this->_total = $this->_total * 1.13;
 				}
 				if($this->_user->goal == 'Maintain & Tone'){
 					$this->_total = $this->_total * 0.955;
 				}
 				
 				$this->_carbs 	= ( 40 / 100 * $this->_total) / 4;
 				$this->_protein = ( 40 / 100 * $this->_total) / 4;
 				$this->_fat 	= ( 20 / 100 * $this->_total) / 9;
 			
 				break;
 		
 		}
 		
 		return array(
 			'cals'=>$this->_total,
 			'carbs'=>$this->_carbs,
 			'fat'=>$this->_fat,
 			'protein'=>$this->_protein,
 			'fiber'=>$this->_fiber
 		);
 	}
 	
 	/**
 	 * getMealRequirement function.
 	 * 
 	 * @access public
 	 * @param mixed $id
 	 * @param mixed $meal_id
 	 * @return void
 	 */
 	public function getMealRequirement($id, $meal_id){
 	
 		$this->getTargets($id);
 		
 		$this->required_stats['cals'] 		= ceil( $this->_total / $this->_meal_breakup[$meal_id] );
		$this->required_stats['protein'] 	= ceil( $this->_protein / 6 );
		$this->required_stats['fat'] 		= ceil( $this->_fat / 6 );
		$this->required_stats['carbs'] 		= ceil( $this->_carbs / $this->_carbs_breakup[$meal_id] );

		if($this->_user->gender == 'Male'){
 			$this->required_stats['fiber'] 	= $this->_fiber/$this->_fiber_male_breakup[$meal_id];
		}
		
		if($this->_user->gender == 'Female'){
 			$this->required_stats['fiber'] 	= $this->_fiber/$this->_fiber_female_breakup[$meal_id];
		}
		
		return $this->required_stats;
 	}
 	
}