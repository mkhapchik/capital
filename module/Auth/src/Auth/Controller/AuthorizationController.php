<?php
namespace Auth\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Auth\Model\SessionTable;
use Auth\Model\User;
use Auth\Controller\AuthenticationController;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

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
	private $config;
	private $acl;
	
	
		
	/**
	*  Проверка доступа
	* @param $routeMatch
	* @return $code - код доступа, self::CODE_ACCESS_IS_ALLOWED - доступ разрешен
	*/
	public function checkAccess($routeMatch)
	{
		$result = self::CODE_ACCESS_IS_DENIED;
		
		$serviceLocator = $this->getServiceLocator();
		$authenticationService = $serviceLocator->get('AuthenticationService');
		
		try
		{
			if(!$authenticationService->hasIdentity()) throw new \Exception(self::CODE_ACCESS_IS_DENIED);
			
			$storage = $authenticationService->getStorage();
			if($storage->isEmpty()) throw new \Exception(self::CODE_ACCESS_IS_DENIED);
			
			$storage_data = $storage->read();
			if(!isset($storage_data['token'])) throw new \Exception(self::CODE_ACCESS_IS_DENIED);
			
			$remote = new \Zend\Http\PhpEnvironment\RemoteAddress();
			$ip = $remote->getIpAddress();
			
			if(!$this->checkIpInAllowedLists($ip)) throw new \Exception(self::CODE_ACCESS_IS_DENIED_BY_IP_NOT_IN_ALLOWED_LIST);
			
			$sessionTable = $serviceLocator->get('SessionTable');
			$this->session = $sessionTable->getSession($storage_data['token'], $ip);
			if(!isset($this->session->user_id)) throw new \Exception(self::CODE_ACCESS_IS_DENIED);
			
			$this->initUser($this->session->user_id);
			if($this->getConfig('use_acl'))
			{
				$this->initAcl();
			}
			
			$inactivityTime = $this->getConfig('inactivity_time_min')*60;
			$lastActivity = strtotime($this->session->lastActivity);
						
			if((time()-$lastActivity)<=$inactivityTime)
			{
				if($this->getConfig('use_acl'))
				{
					//$this->initAcl();

					
					
					$resource = $this->getResource($routeMatch);
					$action = $routeMatch->getParam('action');			
					
					if(!$this->isAllowed($resource, $action)) 
					{
						throw new \Exception(self::CODE_ACCESS_IS_DENIED);
					}
				}
								
				$result = self::CODE_ACCESS_IS_ALLOWED;
				$newLastActivity = time();
				$sessionTable->save(array('last_activity'=>date('Y-m-d H:i:s',$newLastActivity)), $this->session->id);
				$storage->clear();
				$storage->write(array('token'=>$storage_data['token'], 'last_activity'=>$newLastActivity));		
			}
			else
			{
				$authenticationController = $serviceLocator->get('AuthenticationController');
				$authenticationController->logoutAction(false, SessionTable::METHOD_CLOSE_TIMEOUT);
				
				throw new \Exception(self::CODE_ACCESS_IS_DENIED_BY_TIMEOUT);
			}
			
		}
		catch(\Exception $e)
		{
			$result = $e->getMessage();
		}
		
		return $result;			
	}
	
	private function getResource($routeMatch)
	{
		$serviceLocator = $this->getServiceLocator();
		$routName = $routeMatch->getMatchedRouteName();
		$params = $routeMatch->getParams();
			
		$sys_params = array('__NAMESPACE__', 'controller', '__CONTROLLER__', 'action');
		$params = array_filter($params, function($k) use($sys_params){
			return !in_array($k, $sys_params);
		}, ARRAY_FILTER_USE_KEY);
		
		$routesModel = $serviceLocator->get('RoutesTable');
		
		$routerId = $routesModel->getRouterIdByRoute($routName, $params);
		
		return $routerId;
	}
	
	private function initAcl()
	{
		$this->acl = new Acl();
		$userObj = $this->getUser();
		$roles = $userObj->getRoles();
		if(is_array($roles) && count($roles)>0)
		{
			$serviceLocator = $this->getServiceLocator();
			$rolesId = array_keys($roles);
			$permissionsTable = $serviceLocator->get('PermissionsTable');
			$permissions = $permissionsTable->getPermissions($rolesId, $userObj->id);
			
			foreach($permissions as $p)
			{
				$resource = $p['routesId'];
				$role = $p['role_name'];
				$user = $p['user'];
				$privilege = $p['privilege_name'];
				
				if($p['user']) $role = 'user_'.$user;
				else $role = 'role_'.$role;
				
				if(!$this->acl->hasResource($resource))$this->acl->addResource(new Resource($resource));
				if(!$this->acl->hasRole($role))	$this->acl->addRole(new Role($role));
	
				if($p['allow']) 
				{
					$this->acl->allow($role, $resource, $privilege);
				}
				else $this->acl->deny($role, $resource, $privilege);
			}
			
		}
	}
	
	public function isAllowed($resource, $privilege)
	{
		$result = false;
		if(!isset($this->acl)) $this->initAcl();
		
		if($this->acl->hasResource($resource))
		{
			$userId = $this->user->id;
			$roles = $this->user->getRoles();
		
			if($this->acl->hasRole('user_'.$userId))
			{
				$result = $this->acl->isAllowed('user_'.$userId, $resource, $privilege);
			}
			else
			{
				foreach($roles as $role)
				{
					if($this->acl->hasRole('role_'.$role->name))
					{
						$result = $result || $this->acl->isAllowed('role_'.$role->name, $resource, $privilege);
					}
				}
			}
		}
		
		return $result;
	}
	
	private function initUser($userId)
	{
		$serviceLocator = $this->getServiceLocator();
		
		$userTable = $serviceLocator->get('UserTable');
		$this->user = $userTable->get(array('id'=>$userId));
		
		$roleTable = $serviceLocator->get('RoleTable');
		$roles = $roleTable->getRolesByUserId($userId);
		$this->user->setRoles($roles);
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
		$result=false;
			
		if($this->getConfig('use_allow_list_ip'))
		{
			$serviceLocator = $this->getServiceLocator();
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
		if(!isset($this->user)) $this->user = new User();
		return  $this->user;
	}
	
	public function getSession()
	{
		return isset($this->session) ? $this->session : false;
	}
	
	public function getConfig($name=false)
	{
		if(!isset($this->config))
		{
			$serviceLocator = $this->getServiceLocator();
			$config = $serviceLocator->get('config');
			if(isset($config['auth'])) $this->config = $config['auth'];
			else throw new \Exception('Not found section configuration "auth"');
		}
		
		if($name)
		{
			if(!isset($this->config[$name])) throw new \Exception("Not found section configuration of auth '$name'");
			else return $this->config[$name];
		}
		else return $this->config;
	}
}