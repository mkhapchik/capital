<?php
namespace Pages;

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
				'Pages/Model/MenuTable' => function($sm){
					return new \Pages\Model\MenuTable('pages_menu');
				}
			)
		);
    }
	
	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'MenuHelper' => function($helpers){
						$vh = new \Pages\View\Helper\MenuHelper();
						return $vh;
				}
			)
		);
	}
}
