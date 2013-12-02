<?php

/**
 * Model_DbTable_User_Weight class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_User_Weight extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_user_weight')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_user_weight';

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