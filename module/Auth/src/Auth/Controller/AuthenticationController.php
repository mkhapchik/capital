<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;

use Zend\Authentication\Storage\Session as SessionAuth;

class AuthenticationController extends AbstractActionController
{
	public function loginAction()
	{
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
				
				$authService = $this->getServiceLocator()->get('AuthenticationService');
				
				$authServiceAdapter = $authService->getAdapter();
				$authServiceAdapter->setIdentity($login);
				$authServiceAdapter->setCredential($pwd);

				$result = $authServiceAdapter->authenticate();
				
				$userTable = $this->getServiceLocator()->get('UserTable');
				$sessionTable = $this->getServiceLocator()->get('SessionTable');
				
				$config = $this->getServiceLocator()->get('config');
				$authConfig = $config['auth'];
				
				if($result->isValid())
				{
					$user = $userTable->getUserByLogin($login);
					
					if($user->isBlocked())
					{
						$err_message = 'Пользователь заблокирован';
					}
					else
					{	
						$storage = $authService->getStorage();
						if(!$storage->isEmpty()) 
						{
							$storage_data = $storage->read();
							if(isset($storage_data['token'])) $sessionTable->close($storage_data['token']);
													
							$storage->clear();
						}
						
						$sessionTable->closeAll($user->id);
						$userTable->unlock($user->id);
						
						$token = md5(uniqid(rand(), 1));
						$lastActivity = time();
						
						$storage->write(array('token'=>$token, 'last_activity'=>$lastActivity));
						
						
						$sessionData = array();
						
						$sessionData['token'] = $token;
						$sessionData['user_id'] = $user->id;
						
						$remote = new \Zend\Http\PhpEnvironment\RemoteAddress();
						$sessionData['ip'] = $remote->getIpAddress();
												
						$sessionTable->save($sessionData);
						
						$this->redirect()->toRoute($authConfig['success_login_redirect_router']);
					}
				}
				else
				{
					$userTable->incrementCounterFailures($login, $authConfig['max_counter_failures']);
					
					$err_message = 'Неверный логин или пароль';
				}
            }
			else
			{
				
			}
			
			
        }

		return array('form' => $form, 'is_success'=>0, 'message'=>$err_message);
	}
	
	public function logoutAction()
	{
		$authService = $this->getServiceLocator()->get('AuthenticationService');
		$storage = $authService->getStorage();
		if(!$storage->isEmpty()) 
		{
			$storage_data = $storage->read();
			
			if(isset($storage_data['token'])) 
			{
				$sessionTable = $this->getServiceLocator()->get('SessionTable');
				$sessionTableClassName = get_class($sessionTable);
				$method_close = $sessionTableClassName::METHOD_CLOSE_MANUALLY;
				
				$sessionTable->close($storage_data['token'], $method_close);
			}
									
			$storage->clear();
		}
		
		$config = $this->getServiceLocator()->get('config');
		$authConfig = $config['auth'];
		$this->redirect()->toRoute($authConfig['logout_redirect_router']);
	}
}