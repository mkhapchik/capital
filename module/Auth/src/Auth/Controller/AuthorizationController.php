<?php
namespace Auth\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Auth\Model\SessionTable;
use Auth\Controller\AuthenticationController;

class AuthorizationController extends AbstractActionController
{
	const CODE_ACCESS_IS_ALLOWED = 1;
	const CODE_ACCESS_IS_DENIED = 0;
	const CODE_ACCESS_IS_DENIED_BY_TIMEOUT = -1;
	const CODE_ACCESS_NULL = 2;
	const CODE_ACCESS_IS_USER_BLOCKED = -2;
	const CODE_ACCESS_IDENTITY_FAILED = -3;
	
	private $user;
	private $session;
	private $storage;
	
	/**
	*  Проверка доступа
	* @return $code:
	* 0 - Доступ запрещен в соответствии с правами доступа
	* 1 - Доступ разрешен
	* -1- Таймаут неактивности
	*/
	public function checkAccess()
	{
		$result = self::CODE_ACCESS_IS_DENIED;
			
		$session = $this->getSession();
				
		if(isset($session->user_id))
		{
			$this->setUser($session->user_id);

			if($this->checkTimeout())
			{
				$result = self::CODE_ACCESS_IS_ALLOWED;
				$this->updateLastActivity();
			}
			else
			{
				$result = self::CODE_ACCESS_IS_DENIED_BY_TIMEOUT;
				$serviceLocator = $this->getServiceLocator();
				$authenticationController = $serviceLocator->get('AuthenticationController');
				$authenticationController->logoutAction(false, SessionTable::METHOD_CLOSE_TIMEOUT);
			}
		}

		return $result;
	}
	
	private function updateLastActivity()
	{
		$serviceLocator = $this->getServiceLocator();
		$sessionTable = $serviceLocator->get('SessionTable');
		$session = $this->getSession();
				
		$newLastActivity = time();
		$sessionTable->save(array('last_activity'=>date('Y-m-d H:i:s',$newLastActivity)), $session->id);
		
		$this->updateStorage($session->token, $newLastActivity);
	}
	
	private function updateStorage($token, $last_activity)
	{
		$storage = $this->getStorage();
		$storage->clear();
		$storage->write(array('token'=>$token, 'last_activity'=>$last_activity));
	}
	

	public function checkTimeoutAction()
	{
		$result = false;
		$storage = $this->getStorage();
		if($storage && !$storage->isEmpty())
		{
			$storage_data = $storage->read();
			
			$lastActivity = isset($storage_data['last_activity']) ? $storage_data['last_activity'] : 0;
			
			$config = $this->getServiceLocator()->get('config');
			$inactivityTime = $config['auth']['inactivity_time_min']*60;
			
			if((time()-$lastActivity)>$inactivityTime)
			{
				if($this->checkTimeout())
				{
					$session = $this->getSession();
					if($session) $this->updateStorage($session->token, $session->last_activity);
				}
				else
				{
					$result = true;
				}
			}
		}
		
		if($result===false)
		{
			echo 0;
			exit();
		}
		else
		{
			$view = $this->forward()->dispatch('Auth\Controller\Authentication', array(
				'action' => 'login',
				'is_success'=>0,
				'codeAccess'=>self::CODE_ACCESS_IS_DENIED_BY_TIMEOUT
			));
			$view->setTerminal(true);
			return $view;
		}
		
	}
	
	public function getUser()
	{
		return isset($this->user) ? $this->user : false;
	}
	
	/**
	* получение сессии
	*/
	private function getSession()
	{
		if(!isset($this->session))
		{
			$this->session=false;
			$serviceLocator = $this->getServiceLocator();
			$storage = $this->getStorage();
		
			if($storage && !$storage->isEmpty()) 
			{
				$storage_data = $storage->read();
				if(isset($storage_data['token'])) 
				{
					$sessionTable = $serviceLocator->get('SessionTable');
					$token = $storage_data['token'];
					
					$remote = new \Zend\Http\PhpEnvironment\RemoteAddress();
					$ip = $remote->getIpAddress();
					
					$this->session = $sessionTable->getSession($token, $ip);
				}
			}

		}
		
		return $this->session;
	}
	
	/**
	* Получение php сессии аутентификации
	*/
	private function getStorage()
	{
		if(!isset($this->storage))
		{
			$serviceLocator = $this->getServiceLocator();
			$authenticationService = $serviceLocator->get('AuthenticationService');
			
			if($authenticationService->hasIdentity()) $this->storage = $authenticationService->getStorage();
			else $this->storage = false;
		}
		return $this->storage;
	}
	
	/**
	* Установка объекта user
	*/
	private function setUser($id)
	{
		$serviceLocator = $this->getServiceLocator();
		$userTable = $serviceLocator->get('UserTable');
		$this->user = $userTable->get($id);
	}
	
	
	/**
	* Проверка таймаута
	*/
	private function checkTimeout()
	{
		$config = $this->getServiceLocator()->get('config');
		$session = $this->getSession();
		
		$inactivityTime = $config['auth']['inactivity_time_min']*60;
		$lastActivity = strtotime($session->lastActivity);
		
		return ((time()-$lastActivity)<=$inactivityTime);
	}

}