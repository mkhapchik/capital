<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class AuthenticationController extends AbstractActionController
{
	/**
	 *  Объект AuthenticationService
	 */
	private $authservice;
	
	public function loginAction()
	{
		session_start();
		var_dump(date("Y-m-d H:i:s",$_SESSION['__ZF']['_REQUEST_ACCESS_TIME']));
		$err_message='';
		$form = new LoginForm('loginForm');
		$form->setAttribute('action', '/auth/login');
   
        $request = $this->getRequest();
        if ($request->isPost()) 
		{
            $form->setData($request->getPost());
 
            if ($form->isValid()) 
			{
				$dataForm = $form->getData();
				$login = $dataForm['login'];
				$pwd = $dataForm['pwd'];
				
				$authServiceAdapter = $this->getAuthService()->getAdapter();
				$authServiceAdapter->setIdentity($login);
				$authServiceAdapter->setCredential($pwd);

				$result = $authServiceAdapter->authenticate();
				
				$userTable = $this->getServiceLocator()->get('UserTable');
				
				if($result->isValid())
				{
					$user = $userTable->getUserByLogin($login);
					if($user->isBlocked())
					{
						$err_message = 'Пользователь заблокирован';
					}
					else
					{	
						$hash = md5(uniqid(rand(), 1));
						$storage = $this->getAuthService()->getStorage();
						$storage->write($hash);
					}
				}
				else
				{
					$config = $this->getServiceLocator()->get('config');
						
					$userTable->incrementCounterFailures($login, $config['max_counter_failures']);
					
					$err_message = 'Неверный логин или пароль';
				}
			
				
				//return $this->redirect()->toRoute('categories/income');
            }
			else
			{
				//return $this->redirect()->toRoute('/auth/login/err/1');
			}
			
			
        }
		
		
		var_dump($this->getAuthService()->getStorage()->read());	
		return array('form' => $form, 'err_message'=>$err_message);
				
		//return array('form' => $form, 'is_success'=>$is_success, 'message'=>$message);
	}
	
	protected function getAuthService()
	{
		if(!$this->authservice)
		{
			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'users', 'login', 'pwd', 'MD5(?)');
			
			$authService = new AuthenticationService(null, $dbTableAuthAdapter);
			
			$this->authservice = $authService;
		}
		return $this->authservice;
	}
	
	
}