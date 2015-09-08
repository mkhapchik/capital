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
	const CODE_ACCESS_IS_DENIED_BY_IP_NOT_IN_ALLOWED_LIST = -4;
	
	private $user;
	
	private $session;
	
	/**
	*  Проверка доступа
	* @return $code:
	* 0 - Доступ запрещен в соответствии с правами доступа
	* 1 - Доступ разрешен
	* -1- Таймаут неактивности
	*/
	public function checkAccess()
	{
		$config = $this->getServiceLocator()->get('config');
		$authConfig = $config['auth'];
		
		$result = self::CODE_ACCESS_IS_DENIED;
		
		$serviceLocator = $this->getServiceLocator();
		$authenticationService = $serviceLocator->get('AuthenticationService');
		
		if($authenticationService->hasIdentity())
		{
			$storage = $authenticationService->getStorage();
			
			if(!$storage->isEmpty()) 
			{
				$storage_data = $storage->read();
				if(isset($storage_data['token'])) 
				{
					$remote = new \Zend\Http\PhpEnvironment\RemoteAddress();
					$ip = $remote->getIpAddress();
					
					if($this->checkIpInAllowedLists($ip))
					{
						$sessionTable = $serviceLocator->get('SessionTable');
						$token = $storage_data['token'];

						$this->session = $sessionTable->getSession($token, $ip);
						
						if(isset($this->session->user_id))
						{
							$userTable = $serviceLocator->get('UserTable');
							$this->user = $userTable->get($this->session->user_id);
							
							$inactivityTime = $authConfig['inactivity_time_min']*60;
							$lastActivity = strtotime($this->session->lastActivity);
										
							if((time()-$lastActivity)<=$inactivityTime)
							{
								$result = self::CODE_ACCESS_IS_ALLOWED;
								$newLastActivity = time();
								$sessionTable->save(array('last_activity'=>date('Y-m-d H:i:s',$newLastActivity)), $this->session->id);
								$storage->clear();
								$storage->write(array('token'=>$token, 'last_activity'=>$newLastActivity));		
							}
							else
							{
								$result = self::CODE_ACCESS_IS_DENIED_BY_TIMEOUT;
								$authenticationController = $serviceLocator->get('AuthenticationController');
								$authenticationController->logoutAction(false, SessionTable::METHOD_CLOSE_TIMEOUT);
							}
						}
					}
					else
					{
						$result = self::CODE_ACCESS_IS_DENIED_BY_IP_NOT_IN_ALLOWED_LIST;
					}
				}
			}
		}
		
		return $result;
	}
	
	public function checkTimeoutAction()
	{
		$authService = $this->getServiceLocator()->get('AuthenticationService');
		$storage = $authService->getStorage();
		
		if(!$storage->isEmpty())
		{
			$storage_data = $storage->read();
			
			$lastActivity = isset($storage_data['last_activity']) ? $storage_data['last_activity'] : 0;
			
			$config = $this->getServiceLocator()->get('config');
			$authConfig = $config['auth'];
			$inactivityTime = $authConfig['inactivity_time_min']*60;
			
			if((time()-$lastActivity)<=$inactivityTime)
			{
				$result = false;
			}
			else
			{
				$view = $this->forward()->dispatch('Auth\Controller\Authentication', array(
					'action' => 'login',
					'is_success'=>0,
					'codeAccess'=>self::CODE_ACCESS_IS_DENIED_BY_TIMEOUT,
					'is_ajax'=>true
				));
				$view->setTerminal(true);
				return $view;
			}
		}
		else $result = false;
		
		if(!$result)
		{
			echo 0;
			exit();
		}
		else return $result;
	}
	
	public function checkIpInAllowedLists($ip)
	{
		$serviceLocator = $this->getServiceLocator();
		$config = $serviceLocator->get('config');
		$result=false;
		if($config['auth']['use_allow_list_ip'])
		{
			$ipAllowedListTable = $serviceLocator->get('IpAllowedListTable');
			$result = $ipAllowedListTable->is_allowed($ip);	
		}
		else
		{
			$result=true;
		}
		return $result;
	}
	
	public function getUser()
	{
		return isset($this->user) ? $this->user : false;
	}
	
	public function getSession()
	{
		return isset($this->session) ? $this->session : false;
	}

}