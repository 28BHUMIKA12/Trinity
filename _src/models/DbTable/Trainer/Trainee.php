<?php

/**
 * Model_DbTable_Trainer_Trainee class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Trainer_Trainee extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_trainer_trainee')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_trainer_trainee';

	/**
	 * _primary
	 * 
	 * (default value: 'id')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_primary = 'id';
	
	CONST PENDING = 'PENDING';
	CONST ACCEPTED = 'ACCEPTED';
	//CONST DECLINE = 'DECLINE';
	
	
	/**
	 * _dependentTables
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_dependentTables = array(
	);
	
	/**
	 * getTrainerCount function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function getTrainer($id){
	
		$data = $this->fetchRow("trainee_id='{$id}'");
		
		$User = new Model_DbTable_User;
		return $User->doRead($data->trainer_id);
	}
	
	/**
	 * getTraineeCount function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function getTraineeCount($id){
		
		return $this->getCount("trainer_id='{$id}'");
	}

	/**
	 * getTrainee function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function getTrainees($id){
		
		return $this->fetchAll("trainer_id='{$id}'");
	}
	
}