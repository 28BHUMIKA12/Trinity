<?php

/**
 * Model_DbTable_Item class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Item extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_item')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_item';

	//eaten = grocery item, eat out = restaurant meal , cookbook meals
	CONST GROCERY = 'GROCERY';
	CONST RESTAURANT = 'RESTAURANT';
	CONST COOKBOOK = 'COOKBOOK';
	
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
		
		'Parent' => array(
			'columns'           => 'parent_id',
			'refTableClass'     => 'Model_DbTable_Item',
			'refColumns'        => 'id'
		)
	);	
	
	
}