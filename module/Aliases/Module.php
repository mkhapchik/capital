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

		$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'addAliasRoutes'), 3);
		//$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'initAlias'), 3);
    }
	
	/*
	public function initAlias(MvcEvent $e)
	{
		$app = $e->getApplication();
		$sm = $app->getServiceManager();
		$AliasesModel = $sm->get('Aliases\Model\AliasesModel');
	
		$request = $sm->get('Request');
		$uri = '/'.trim($request->getRequestUri(), '/');
	
		$aliases = $AliasesModel->match($uri);
		
		if($aliases)
		{
			$router = $e->getRouter();
			
			if($router->hasRoute($aliases['route_name']))
			{
				$config = $sm->get('config');
				$route_config = $config['router']['routes'];
				$route_name_list = explode('/', $aliases['route_name']);
				$i=0;
				foreach($route_name_list as $name) 
				{
					if($i>0) $route_config = $route_config['child_routes'][$name];
					else $route_config = $route_config[$name];
					$i++;
				}
			
				$options = $route_config['options'];
				if(is_array($aliases['route_params'])) $options['defaults'] = array_merge($options['defaults'], $aliases['route_params']);
								
				$options['route'] = $aliases['uri']."[/]";
				
				$route = \Zend\Mvc\Router\Http\Segment::factory($options);	
				
				
				
					//$router->removeRoute($route_name);
				
				$router->addRoute($aliases['name'], $route);	
			}
		
		}
		
	}
	*/
		
	public function addAliasRoutes(MvcEvent $e)
	{
		$app = $e->getApplication();
		$sm = $app->getServiceManager();
		$AliasesModel = $sm->get('Aliases\Model\AliasesModel');
		$aliases = $AliasesModel->getAliases();
	
		foreach($aliases as $alias)
		{
			$route_name = $alias['route_name'];
			$params = (array)json_decode($alias['route_params']);
						
			$router = $e->getRouter();
			
			if($router->hasRoute($route_name))
			{
				$options = $this->getRouterOptions($sm, $route_name);
				
				if(is_array($params)) $options['defaults'] = array_merge($options['defaults'], $params);
				$options['route'] = $alias['uri']."[/]";
								
				$route = \Zend\Mvc\Router\Http\Segment::factory($options);
				$router->addRoute($alias['name'], $route);
			}
		}
	}	
	
	private function getRouterOptions($sm, $route_name)
	{
		$config = $sm->get('config');
		$route_config = $config['router']['routes'];
		$route_name_list = explode('/', $route_name);
		$i=0;
		foreach($route_name_list as $name) 
		{
			if($i>0) $route_config = $route_config['child_routes'][$name];
			else $route_config = $route_config[$name];
			$i++;
		}
		
		$options = $route_config['options'];
		if(!isset($options['defaults'])) $options['defaults'] = array();
		
		return $options;
	}

}
