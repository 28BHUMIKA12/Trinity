<?php

/**
 * Model_DbTable_User class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_User extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_user')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_user';

	/**
	 * _primary
	 * 
	 * (default value: 'id')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_primary = 'id';
	
	CONST NOT_CONFIRMED 	= 'NOT_CONFIRMED';
	CONST CONFIRMED 		= 'CONFIRMED';
	
	CONST ADMIN = 'Admin';
	CONST TRAINER = 'Trainer';
	CONST TRAINEE = 'Trainee';
	
	/**
	 * sevenDay
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $sevenDay = array(
		
		'water' => array(1.15,1.15,1.15,1.15,1,0.5,0),
		'carbs' =>  array(1,1,0.7,0.7,3,3,0.4),
		'protein' => array(1.5,1.5,1.5,1.5,1,1,0.2),
		'fat' => array(0.5,0.5,0.6,0.6,0.4,0.4,0)
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
	 * getSevenDaysOut function.
	 * 
	 * @access public
	 * @param mixed $day
	 * @return void
	 */
	public function getSevenDaysOut($day,$auth){
	
		$user = $this->doRead($auth->id);
		
		$data = array();
		
		$data['water']		= $this->sevenDay['water'][$day-1] * $user->weight_lbs; 
		$data['carbs']		= $this->sevenDay['carbs'][$day-1] * $user->weight_lbs;
		$data['protein']	= $this->sevenDay['protein'][$day-1] * $user->weight_lbs;
		$data['fat']		= $this->sevenDay['fat'][$day-1] * $user->weight_lbs;
		
		//print_r($data);
		return $data;
		
	}
}