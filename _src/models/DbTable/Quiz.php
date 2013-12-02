<?php

/**
 * Model_DbTable_Quiz class.
 * 
 * @extends Model_DbTable_Quiz
 */
class Model_DbTable_Quiz extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_quiz')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_quiz';

	/**
	 * _primary
	 * 
	 * (default value: 'id')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_primary = 'id';
	
	CONST MAXQUESTIONPLAYLIMIT = 7;
	/**
	 * _dependentTables
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_dependentTables = array(
	);
	
	
	
}