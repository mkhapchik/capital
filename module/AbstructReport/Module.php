<?php
namespace AbstructReport;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

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
	
	public function onBootstrap(MvcEvent $e)
    {
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'Report\Form\FilterForm'=>function($sm){
					return new \Report\Form\FilterForm('filter');
				}
			),
		);
	}
	
	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'FormattingHelper' => function($sm){
						$vh = new \Report\View\Helper\FormattingHelper();
						return $vh;
				}
			)
		);
	}
	
	
}
