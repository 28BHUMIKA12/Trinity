<?php

/**
 * Model_DbTable_User_Exercise class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_User_Exercise extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_user_exercise')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_user_exercise';

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
	protected $_dependentTables = array();
	
	/**
	 * initiate function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function initiate($id){
	
		$this->getDefault($id);
		
		
	}	
	
	/**
	 * getDefault function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function getDefault($id){
	
		$exercise = new Model_DbTable_Exercise;
		$exercise_default = new Model_DbTable_Exercise_Default;
		$user_exercise = new Model_DbTable_User_Exercise;
		$user = new Model_DbTable_User;
		
		$user = $user->doRead($id);
		
		// get all that match user
		$select = $exercise_default->select();
		$select->where("body_type='".$user->body_type."'");		
		$select->where("gender='".$user->gender."'");		
		$select->where("goal='".$user->goal."'");		
		$data = $exercise_default->fetchAll($select);
				
		// insert into user exercise
		foreach($data as $key=>$value){
			
			$exercise_data = $exercise->doRead($value->exercise_id);
			$insert['user_id'] = $id;
			$insert['weekday'] = $value->weekday;
			$insert['pic'] = $exercise_data->pic;
			$insert['video'] = $exercise_data->video;
			$insert['title'] = $exercise_data->title;
			$insert['description'] = $exercise_data->description;
			$insert['dated'] = time();
			
			$user_exercise->doCreate($insert);
		}
	}
	
}