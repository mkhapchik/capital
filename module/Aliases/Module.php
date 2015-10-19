<?php
namespace Aliases;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;

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
				'Aliases\Model\AliasesModel' => function($sm){
					return new \Aliases\Model\AliasesModel('aliases');
				}
				
			)
		);
    }
	
	/**
	* Обработчик события "начальная загрузка"
	*/
	public function onBootstrap(MvcEvent $e)
    {		
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

		$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'addAliasRoute'), 3);
    }
		
	public function addAliasRoute(MvcEvent $e)
	{
		$app = $e->getApplication();
		$sm = $app->getServiceManager();
		$AliasesModel = $sm->get('Aliases\Model\AliasesModel');
		$aliases = $AliasesModel->fetchAll();
		
		foreach($aliases as $alias)
		{
			$route_name = $alias['route'];
			$params = unserialize($alias['params']);
			
			$router = $e->getRouter();
			
			if($router->hasRoute($route_name))
			{
				$config = $sm->get('config');
				$route_config = $config['router']['routes'];
				$route_name_list = explode('/', $route_name);
				foreach($route_name_list as $name) $route_config = $route_config[$name];
				
				$options = $route_config['options'];
				
				if(is_array($params)) $options['defaults'] = array_merge($options['defaults'], $params);
								
				$options['route'] = $alias['hfu']."[/]";
				
				$router = $e->getRouter();
				//$router->removeRoute($route_name);
				$route = \Zend\Mvc\Router\Http\Segment::factory($options);
				
				$newRouteName = $AliasesModel->getRouteNameByAliasId($alias['id']);
				$router->addRoute($newRouteName, $route);	
				
			}
		}
	}	

}
