<?php

/**
 * Model_Custom_File class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_Custom_File extends Core_Db{	


	/**
	 * upload function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $params
	 * @return void
	 */
	public static function upload($file,$destination,$time){
		
		// if image posted
		if($file['tmp_name']){
			
			$destination = $destination.$time.'_'.$file['name'];
			
			// if file actually uploaded to temp on server
			if(move_uploaded_file($file['tmp_name'],$destination)){
			
				// change permission
				if(!chmod($destination,0777))
					return false;
				else
					return true;
			}
		}
	}
}

