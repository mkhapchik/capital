<?php
namespace Auth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
	private $sm;
	private $pm;
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->pm = $serviceLocator;
		$this->sm = $serviceLocator->getServiceLocator();
	}

    public function getServiceLocator()
	{
		return $this->sm;
	}
	
	
	public function __invoke()
	{
		return $this;
	}
	
	
	public function timeoutScript()
	{
		$config = $this->sm->get('config');
		
		var_dump(get_class($this->pm->get('url')));
		
		$view = new ViewModel(array(
			'frequency' => $config['auth']['frequency_of_check_timeout_sec'],
			'url' => '/auth/timeout'
		));
 
		//$view->setTerminal(true);
		$view->setTemplate('auth/AuthHelper/timeoutScript');

        $partialHelper = $this->view->plugin('partial');
		
		
        return $partialHelper($view);
		
	}
}
?>