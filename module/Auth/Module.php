<?php
namespace Auth;

use Auth\Model\UserTable;
use Auth\Model\SessionTable;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Auth\Controller\AuthorizationController;
use Auth\Controller\AuthenticationController;

class Module
{
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
