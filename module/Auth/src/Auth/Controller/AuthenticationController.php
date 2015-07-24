<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Storage\Session as SessionAuth;
use Auth\Form\LoginForm;
use Auth\Controller\AuthorizationController;
use Auth\Model\SessionTable;

class AuthenticationController extends AbstractActionController
{
	public function loginAction()
	{
		$codeAccess = $this->params()->fromRoute('codeAccess', AuthorizationController::CODE_ACCESS_NULL);
		$is_success = $this->params()->fromRoute('is_success', 1);
		
		switch($codeAccess)
		{
			case AuthorizationController::CODE_ACCESS_NULL:
				$message = '';
				break;
			case AuthorizationController::CODE_ACCESS_IS_ALLOWED:
				$message = '';
				break;
			case AuthorizationController::CODE_ACCESS_IS_DENIED : 
				$message = 'Доступ запрещен!'; 
				break;
			case AuthorizationController::CODE_ACCESS_IS_DENIED_BY_TIMEOUT : 
				$message = 'Таймаут логина!'; 
				break;
			default: 
				$message = 'Доступ запрещен!';
		}

		$form = new LoginForm('loginForm');
		//$form->setAttribute('action', '/auth/login');
   
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
						$is_success=0;
						$message = 'Пользователь заблокирован';
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
						$sessionData['last_activity'] = date('Y-m-d H:i:s',$lastActivity);
						
						$remote = new \Zend\Http\PhpEnvironment\RemoteAddress();
						$sessionData['ip'] = $remote->getIpAddress();
												
						$sessionTable->save($sessionData);
						
						$this->redirect()->toRoute($authConfig['success_login_redirect_router']);
					}
				}
				else
				{
					$userTable->incrementCounterFailures($login, $authConfig['max_counter_failures']);
					
					$is_success=0;
					$message = 'Неверный логин или пароль';
				}
            }
			else
			{
				$is_success=0;
				$message = 'Неверный логин или пароль';
			}
        }
		
		$view = new ViewModel(array('form' => $form, 'is_success'=>$is_success, 'message'=>$message));
		return $view;
	}
	
	public function logoutAction($is_redirect = true, $method_close=SessionTable::METHOD_CLOSE_MANUALLY)
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
				
				$sessionTable->close($storage_data['token'], $method_close);
			}
									
			$storage->clear();
		}
		
		if($is_redirect)
		{
			$config = $this->getServiceLocator()->get('config');
			$authConfig = $config['auth'];
			$this->redirect()->toRoute($authConfig['logout_redirect_router']);
		}
	}
	
	
}