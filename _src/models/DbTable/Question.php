<?php

/**
 * Model_DbTable_Question class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Question extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_question')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_question';

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