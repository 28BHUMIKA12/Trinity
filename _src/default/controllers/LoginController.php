<?php

/**
 * LoginController class.
 * 
 * @extends Zend_Controller_Action
 */
class LoginController extends Zend_Controller_Action{

	
	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){	
		
		//$this->_helper->layout()->setLayout('login');
	}
	
	/**
	 * indexAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function indexAction(){

		
		//	$this->_helper->layout()->disableLayout();
		
		// if already login send to home page 
		if(Zend_Auth::getInstance()->hasIdentity()){
			$this->_redirect("/user/meal/plan");
		}
	
	}
	
	/**
	 * authAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function authAction(){
		
		// check if form is posted
		if($this->getRequest()->isPost()){
			
			if($this->_getParam('username') && $this->_getParam('password')){
				//  init auth process
				if($this->_authProcess()){
					
					$session = new Zend_Session_Namespace;
					$redirect = $session->redirectTo;
					
					$session->redirectTo = '';
					
					if($redirect)
						$this->_redirect($redirect);
					else
						$this->_redirect('/user/meal/plan');
										
				}
				else{
					$this->view->err = 'Invalid Username/Password';
					$this->render('index');
				}
			}
			else{
				
				$this->view->err = 'Invalid Username/Password';
				$this->render('index');
			}
			
		}
		// if coming without post
		else{
			$this->_redirect('/login');	
		}
	}
	
	/**
	 * _authProcess function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _authProcess(){
	
		
		// get param for username and password.
		$username= $this->_getParam("username");
		$passwd  = $this->_getParam("password");
			
		// get the auth adaptor
		$adapter = $this->_getAuthAdapter();
		
		// give username and password to adaptor
		$adapter->setIdentity($username); 
		$adapter->setCredential($passwd);

		//	get auth instance and authenticate the adaptor
		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($adapter);
		
		//	if authentication is valid, store data into auth session and return true else false
		if ($result->isValid()) {
			
			//	get the row of user
			$user = $adapter->getResultRowObject();
			
			// write row info to session
			$auth->getStorage()->write($user);
			
			// and return true
			return true;
		}
		
		// auth failed
		return false;
	
	}
	
	/**
	 * _getAuthAdapter function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _getAuthAdapter() {
    
		// get the database default adaptor
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		
		// get the auth adaptor, it required the dbAdaptor as parameter
		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
		
		// set various auth params, like table username fields, password fields and others treatments
		$authAdapter->setTableName('mus_user')
			->setIdentityColumn('email')
			->setCredentialColumn('password');
			//->setCredentialTreatment('sha1(?)');
			
		// return the adaptor interface
		return $authAdapter;
	}

	/**
	 * quitAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function quitAction(){
	
		// clear the auth indentity, it will clear the auth session
		Zend_Auth::GetInstance()->clearIdentity();
		
		// back to index
		$this->_redirect('/login');
		
	}
	
   	

}