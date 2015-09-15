<?php
namespace Auth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
	protected $sm;
	protected $pm;
	protected $events;
    
	
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
		
		$routeMatch = $this->sm->get('Application')->getMvcEvent()->getRouteMatch();
		
		if($routeMatch) $routeMatchParams = $routeMatch->getParams();
		
		if(!$routeMatch || !isset($routeMatchParams['__CONTROLLER__']) || $routeMatchParams['__CONTROLLER__']!='Authentication')
		{	
			$view = new ViewModel(array(
				'frequency' => $config['auth']['frequency_of_check_timeout_sec'],
				'url' => $this->pm->get('url')->__invoke('auth/timeout')
			));
	 
			$view->setTemplate('auth/authHelper/timeoutScript');

			$partialHelper = $this->view->plugin('partial');
			
			return $partialHelper($view);
		}
			
	}
	
	public function user()
	{
		$authorizationController = $this->sm->get('AuthorizationController');
		$user = $authorizationController->getUser();
		
		$view = new ViewModel();
			
		if($user)
		{
			$logout_url = $this->pm->get('url')->__invoke('auth/logout');
			$view->setVariable('logout_url', $logout_url);
			$view->setVariable('user', $user);
						
			$view->setTemplate('auth/authHelper/user');
		}
		else
		{
			$login_url = $this->pm->get('url')->__invoke('auth/login');
			$view->setVariable('login_url', $login_url);
			
			$view->setTemplate('auth/authHelper/guest');
		}

		$partialHelper = $this->view->plugin('partial');
		return $partialHelper($view);
	}
}
?>