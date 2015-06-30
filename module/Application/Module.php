<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    private $acl;
	
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
	
	public function getConfig()
    {
		return include __DIR__ . '/config/module.config.php';
    }
	
	public function getServiceConfig()
    {
		return array(
            'factories' => array(
                'menu' => function($sm){
                    $menutable = new \Application\Model\MenuTable();
                    return $menutable;
                },
 				'Navigation' => function ($sm) {
					$navigation =  new \Application\Navigation\MyNavigation();
					return $navigation->createService($sm);
				}
			)
		);
    }
	
	/**
	* Обработчик события "начальная загрузка"
	*/
	public function onBootstrap(MvcEvent $e)
    {
		$this->initAcl($e);
		
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

		$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'addRoutes'), 2);
		
		$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkAccess'));
    }
	
	public function addRoutes(MvcEvent $e)
	{
		$router = $e->getRouter();
		$route = \Zend\Mvc\Router\Http\Literal::factory(array(
			'route' => '/foo',
			'defaults' => array(
				'__NAMESPACE__' => 'Transactions\Controller',
				'controller'    => 'TransactionExpense',
				'action'        => 'add',
			),
		));
		//$router->addRoute('account/default', $route);
		$router->addRoute('foo', $route);
	}
	
	public function checkAccess(MvcEvent $e)
	{
		$route = $e->getRouteMatch();
		//\Zend\Debug\Debug::dump($route, '123');
		
	}
	
	public function initAcl(MvcEvent $e)
	{
		//echo __CLASS__;
	}
	
}
