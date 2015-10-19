<?php
namespace Menu;
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
				'Menu\Model\MenuTable' => function($sm){
					return new \Menu\Model\MenuTable('pages_menu');
				},
				'Menu\Service\Menu' => function($sm){
					return new \Menu\Service\Menu();
				},
				
			)
		);
    }
	
	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'MenuHelper' => function($helpers){
						$vh = new \Menu\View\Helper\MenuHelper();
						return $vh;
				}
			)
		);
	}
}
