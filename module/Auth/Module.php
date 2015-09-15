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

class Module
{
    private $event;
	private $acl;
	
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
		$this->event = $e;
		//$this->initAcl($e);
		
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

		$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkAccess'));
		
       // session_start();
        
       // print_r(count($_SESSION['__ZF']));
        
		
    }
	
	
	
	public function checkAccess(MvcEvent $e)
	{
		$serviceManager = $e->getApplication()->getServiceManager();
		$routeMatch = $e->getRouteMatch();
		$routName = $routeMatch->getMatchedRouteName();
		
		$authorizationController = $serviceManager->get('AuthorizationController');
				
		if(!in_array($routName, array('auth/login', 'auth/logout', 'auth/timeout', 'auth/refresh_captcha')))
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
	
	public function initAcl(MvcEvent $e)
	{
		echo __CLASS__;
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
				'AuthHelper' => function($sm) {
						$vh = new \Auth\View\Helper\AuthHelper();
						$vh->setEvent($this->event);

						return $vh;

				}
			)
		);
	}
	
	
}
