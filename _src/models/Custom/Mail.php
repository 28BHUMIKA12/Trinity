<?php

/**
 * Model_Custom_Mail class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_Custom_Mail extends Zend_Mail{	

	CONST SUPPORT = 'support@switchcodes.com';
	CONST NOREPLY = 'noreply@switchcodes.com';
	
	
	/**
	 * _clear function.
	 * 
	 * @access private
	 * @return void
	 */
	private function _clear(){
	
		$this->clearFrom();
		$this->clearSubject();

	}
	
	/**
	 * inviteTrainees function.
	 * 
	 * @access public
	 * @param mixed $trainer
	 * @param mixed $trainee
	 * @return void
	 */
	public function inviteTrainees($trainer,$trainee_email,$if_trainee_found,$LastId){
	    
	    $this->_clear();
	    
	    if(!$if_trainee_found){
		    $html = "Dear {$trainee_email}
		    	
		    	<br><br>
		    	{$trainer->first_name} {$trainer->last_name} has requests to be your health trainer. Click below link to visit and register Trinity.
		    	<br><bR>
		    	<a href='".HTTP.WWW_ROOT."/visitor/register'>Click Here To Register To Trinity.</a>
	
				<Br><br>Support Team,<bR>".APP_NAME.". <br><br>
		    ";
		    
		    $this->addTo($trainee_email,"Trinity User");
		    $this->setFrom(self::NOREPLY, self::NOREPLY);
		    
		}
		else{
			
		    $html = "Dear {$trainee_email->email}
		    	
		    	<br><br>
		    	{$trainer->first_name} {$trainer->last_name} has requests to be your health trainer. Click below link to accept the invitation.
		    	<br><bR>
		    	<a href='".HTTP.WWW_ROOT."/user/public/trainer-request/code/".sha1($LastId)."'>Click Here To See Invitation.</a>
	
				<Br><br>Support Team,<bR>".APP_NAME.". <br><br>
		    ";
			
		    $this->setFrom(self::NOREPLY, self::NOREPLY);
			$this->addTo($trainee_email->email,"Trinity User");
			
			
	    
		}
	    
	    $this->setBodyHtml($html);
		$this->setSubject('Trinity - Trainer Request.');
	 	$this->send();

	}
	
	/**
	 * forgotPassword function.
	 * 
	 * @access public
	 * @param mixed $user
	 * @return void
	 */
	public function forgotPassword($user){
	    
	    $this->_clear();
	    $html = "Dear {$user->first_name}
	    	
	    	<br><br>
	    	You have requested to reset the password. Please follow below link to reset the password.
	    	<br><bR>
	    	<a href='".HTTP.WWW_ROOT."/support/setpwd/code/".sha1($user->id)."'>Click Here.</a>

			<Br><br>Support Team,<bR>".APP_NAME.". <br><br>
	    ";
		
		$this->setBodyHtml($html);
	    
	    $this->setFrom(self::NOREPLY, self::NOREPLY);
	    $this->addTo($user->email, $user->first_name);
	    $this->setSubject('Reset Password');
	 	$this->send();

	}
	
	
}

