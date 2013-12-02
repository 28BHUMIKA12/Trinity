<?php

/**
 * Model_DbTable_Goal class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Goal extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_goal')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_goal';

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