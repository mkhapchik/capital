<?php
namespace Auth;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Auth\Model\UserTable;
use Auth\Model\SessionTable;
use Auth\Model\IpAllowedListTable;
use Auth\Controller\AuthorizationController;
use Auth\Controller\AuthenticationController;
use Zend\View\Model\ViewModel;
//use Zend\ModuleManager as ModuleManager;

class Module
{
	//private $acl;
	private $page;
	
	public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
	/**
	* Обработчик события "начальная загрузка"
	*/
	public function onBootstrap(MvcEvent $e)
    {		
		//$this->initAcl($e);
		
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

		//$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'initPage'));
		$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkAccess'));
    }
	/*
	public function initPage(MvcEvent $e)
	{
		$serviceManager = $e->getApplication()->getServiceManager();
		
		$routeMatch = $e->getRouteMatch();
		
		$routName = $routeMatch->getMatchedRouteName();
		$uri = trim($routeMatch->getParam('uri', null), '/');
		$routeParams = empty($uri) ? null : array('uri'=>$uri);
	
		
		
		$pageModel = $serviceManager->get('Pages\Model\PageModel');
		$pageData = $pageModel->getPageByRoute($routName, $routeParams);
		
		if(count($pageData))
		{
			$page = $this->getPage($serviceManager, $pageData[0]['type_name']);
			$page->exchangeArray($pageData[0]);
			$this->page = $page;
		}
	}
	*/
	
	private function getPage($sm, $type_name=null)
	{
		$page = false;
		switch($type_name)
		{
			default: $page = $sm->get('Page');
		}
		
		return $page;
	}
	
	public function checkAccess(MvcEvent $e)
	{
		$serviceManager = $e->getApplication()->getServiceManager();
		$routeMatch = $e->getRouteMatch();
		$routName = $routeMatch->getMatchedRouteName();

		$authorizationController = $serviceManager->get('AuthorizationController');
		
		$config = $serviceManager->get('config');
		$system_ignore_routes = array('auth/login', 'auth/logout', 'auth/timeout', 'auth/refresh_captcha');
		$ignore_routes = is_array($config['auth']['ignore_routes']) ? $config['auth']['ignore_routes'] : array();
		$ignore_routes = array_merge_recursive($system_ignore_routes, $ignore_routes);
		
		if(!in_array($routName, $ignore_routes))
		{
			$pageId = false;
			/*
			try
			{
				$hfu = $serviceManager->get('hfuModel');
				
				$pageId = $hfu->getPageIdByUri($uri);
				
				
			}
			catch(\Exception $e)
			{
				$params = $routeMatch->getParams();
				var_dump($params);
				
				$pageModel = $this->serviceLocator->get('Pages\Model\PageModel');
				$page = $pageModel->getPageByRoute();
				$pageId = $page->id;
				
				//echo $e->getMessage();
			}
			*/
			// получить страницу по маршруту и параметрам
			$pageId = true;
			
			if(!$pageId)
			{
				$routeMatch->setParam('__NAMESPACE__', 'Zend\Mvc\Controller');
				$routeMatch->setParam('__CONTROLLER__', 'AbstractActionController');
				$routeMatch->setParam('controller', 'Zend\Mvc\Controller\AbstractActionController');
				$routeMatch->setParam('action', 'notFoundAction');
			}
			else
			{
			
				$codeAccess = $authorizationController->checkAccess();
				$authorizationController = $serviceManager->get('AuthorizationController');
			
				if($codeAccess != AuthorizationController::CODE_ACCESS_IS_ALLOWED)
				{
					$routeMatch->setParam('__NAMESPACE__', 'Auth\Controller');
					$routeMatch->setParam('__CONTROLLER__', 'Authentication');
					$routeMatch->setParam('controller', 'Auth\Controller\Authentication');
					$routeMatch->setParam('action', 'login');
					$routeMatch->setParam('codeAccess', $codeAccess);
					$routeMatch->setParam('is_success', 0);
					$routeMatch->setParam('is_ajax', 0);
					
				}
			}
		}
	}
	
	public function initAcl(MvcEvent $e)
	{
		
	}
	
	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'AuthenticationService' => function ($sm){
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'users', 'login', 'pwd', 'MD5(?)');
					$authService = new AuthenticationService(null, $dbTableAuthAdapter);
					return $authService;
				},
				'UserTable' => function ($sm) {
					return new UserTable('users');
				},
				'SessionTable' => function ($sm) {
					return new SessionTable('session');
				},
				'IpAllowedListTable' => function($sm){
					return new IpAllowedListTable('ip_allowed_list');
				},
				'AuthorizationController'=>function($sm){
					return new AuthorizationController();
				},
				'AuthenticationController'=>function($sm){
					return new AuthenticationController();
				}
			),
		);
	}
	
	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'AuthHelper' => function($sm){
						$vh = new \Auth\View\Helper\AuthHelper();
						return $vh;
				}
			)
		);
	}
	
	
}
