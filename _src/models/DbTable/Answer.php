<?php

/**
 * Model_DbTable_Answer class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_DbTable_Answer extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 'mus_answer')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 'mus_answer';

	/**
	 * _primary
	 * 
	 * (default value: 'id')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_primary = 'id';
	
	CONST TEXT 	= 'TEXT';
	CONST IMAGESERIES = 'IMAGESERIES'; 
	CONST VIDEOSERIES = 'VIDEOSERIES'; 
	CONST MULTICHECK = 'MULTICHECK';
	CONST TRUEFALSE = 'TRUEFALSE';
	
	/**
	 * _dependentTables
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_dependentTables = array(
	);
	
	}