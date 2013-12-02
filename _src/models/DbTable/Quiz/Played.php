<?php

/**
 * Model_DbTable_Quiz_Played class.
 * 
 * @extends Model_DbTable_Quiz
 */
class Model_DbTable_Quiz_Played extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_quiz_played')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_quiz_played';

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
	
	
	
}