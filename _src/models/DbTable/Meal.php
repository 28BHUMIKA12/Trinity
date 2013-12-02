<?php

/**
 * Model_DbTable_Meal class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Meal extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_meal')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_meal';

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