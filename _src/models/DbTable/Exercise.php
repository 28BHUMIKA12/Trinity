<?php

/**
 * Model_DbTable_Exercise class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Exercise extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_exercise')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_exercise';

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