<?php

/**
 * Model_DbTable_Meal_Item class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Meal_Item extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_meal_item')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_meal_item';

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