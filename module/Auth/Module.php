<?php
namespace Auth;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Auth\Model\UserTable;
use Auth\Model\SessionTable;
use Auth\Controller\AuthorizationController;
use Auth\Controller\AuthenticationController;
use Zend\View\Model\ViewModel;

class Module
{
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
		//$this->initAcl($e);
		
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

		$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkAccess'));
    }
	
	
	
	public function checkAccess(MvcEvent $e)
	{
		$serviceManager = $e->getApplication()->getServiceManager();
		$routeMatch = $e->getRouteMatch();
		$routName = $routeMatch->getMatchedRouteName();
		
		$authorizationController = $serviceManager->get('AuthorizationController');
		$codeAccess = $authorizationController->checkAccess();
		
		if(!in_array($routName, array('auth/login', 'auth/logout')))
		{
			$authorizationController = $serviceManager->get('AuthorizationController');
		
			if($codeAccess != AuthorizationController::CODE_ACCESS_IS_ALLOWED)
			{
				$routeMatch->setParam('__NAMESPACE__', 'Auth\Controller');
				$routeMatch->setParam('__CONTROLLER__', 'Authentication');
				$routeMatch->setParam('controller', 'Auth\Controller\Authentication');
				$routeMatch->setParam('action', 'login');
				$routeMatch->setParam('codeAccess', $codeAccess);
				$routeMatch->setParam('is_success', 0);
			}
		}
		
		$user = $authorizationController->getUser();
		
		if($user)
		{
			$viewModel = $e->getViewModel();
			$viewModel->setVariable('login', $user->login);
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
					$userTable =  new UserTable();
					return $userTable;
				},
				'SessionTable' => function ($sm) {
					$sessionTable =  new SessionTable();
					return $sessionTable;
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
	
	
}
