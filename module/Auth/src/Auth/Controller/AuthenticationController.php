<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Storage\Session as SessionAuth;
use Auth\Form\LoginForm;
use Auth\Controller\AuthorizationController;
use Auth\Model\SessionTable;
use \Exception;

class AuthenticationController extends AbstractActionController
{
	
	public function loginAction()
	{
		$codeAccess = $this->params()->fromRoute('codeAccess', AuthorizationController::CODE_ACCESS_NULL);
		$is_success = $this->params()->fromRoute('is_success', 1);
				
		$message = $this->getMessageByCode($codeAccess);

		$form = new LoginForm('loginForm');
		
        $request = $this->getRequest();
		$is_xmlhttprequest = $request->isXmlHttpRequest();
		
		if ($request->isPost() && $request->getPost('submit', false)!==false) 
		{
            try
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
							throw new Exception(AuthorizationController::CODE_ACCESS_IS_USER_BLOCKED);
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
							
							if($is_xmlhttprequest)
							{		
								
							}
							else
							{
								$this->redirect()->toRoute($authConfig['success_login_redirect_router']);
							}
						}
					}
					else
					{
						$userTable->incrementCounterFailures($login, $authConfig['max_counter_failures']);
						throw new Exception(AuthorizationController::CODE_ACCESS_IDENTITY_FAILED);
					}
				}
				else
				{
					throw new Exception(AuthorizationController::CODE_ACCESS_NULL);
				}
			}
			catch(Exception $e)
			{
				$code = $e->getMessage();
				$message = $this->getMessageByCode($code);
				$is_success=0;
			}
			
			if($is_xmlhttprequest)
			{		
				
			}
		}
		$view = new ViewModel(array('form' => $form, 'is_success'=>$is_success, 'message'=>$message, 'is_xmlhttprequest' => $is_xmlhttprequest));
		return $view;
	}
	
	/**
	 *  Возвращает сообщение по коду ошибки
	 */
	private function getMessageByCode($code)
	{
		switch($code)
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
			case AuthorizationController::CODE_ACCESS_IS_USER_BLOCKED:
				$message = 'Пользователь заблокирован'; 
				break;
			case AuthorizationController::CODE_ACCESS_IDENTITY_FAILED:
				$message = 'Неверный логин или пароль'; 
				break;
			default: 
				$message = 'Доступ запрещен!';
		}
		
		return $message;
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