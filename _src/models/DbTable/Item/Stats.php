<?php

/**
 * Model_DbTable_Item_Stats class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Item_Stats extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_item_stats')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_item_stats';

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
	 * _referenceMap
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_referenceMap    = array(
		
	);	
	
	
}