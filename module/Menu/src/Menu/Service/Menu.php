<?php
namespace Menu\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManagerAwareInterface;

class Menu implements ServiceLocatorAwareInterface
{
	protected $menu;
	
	protected $sm;
	
	protected $currentUri;
	
	public function setServiceLocator(ServiceLocatorInterface $sm)
	{
		$this->sm = $sm;
	}

    public function getServiceLocator()
	{
		return $this->sm;
	}
	
	public function getMenu($name=false)
	{
		
		if(!$this->hasMenu($name))
		{
			$data = $this->generateMenu($name);
			$this->setMenu($data, $name);
		}
		
		return $name===false ? $this->menu : $this->menu[$name];
	}
	
	public function setCurrentUri()
	{
		$request = $this->sm->get('request');
		$url = parse_url($request->getRequestUri());
		$this->currentUri = $url['path'];
	}
	
	public function getCurrentUri()
	{
		if(!isset($this->currentUri)) $this->setCurrentUri();
		return $this->currentUri;
	}
	
	public function setMenu($data, $name=false)
	{
		if(!is_array($this->menu)) $this->menu = array();
		$this->menu = array_merge_recursive($this->menu, $data);
	}
	
	public function hasMenu($name)
	{
		if(is_array($this->menu) && ($name===false || array_key_exists($name, $this->menu))) return true;
		else return false;
	}
	
	public function generateMenu($name)
	{
		$menuTable = $this->sm->get('Menu\Model\MenuTable');
		$fetchData = $menuTable->fetchMenu($name);
		$structuredList = $this->makeStructuredList($fetchData);
		
		$config = array();
		
		foreach($structuredList as $name=>$menu)
		{
			foreach($menu[0] as $item)
			{
				$page = $this->makePages($name, $structuredList, $item);
				if($page) $config[$name][] = $page;
			}
		}
		
		$app_config = $this->sm->get('config'); 
		$navigation_config = is_array($app_config['navigation']) ? $app_config['navigation'] : array();
		$config = array_merge_recursive($config, $navigation_config);
		
		return $config;
	}
	
	private function makeStructuredList($data)
	{
		$list = array();
		
		foreach($data as $row)
		{
			$list[$row['menu_name']][$row['parent_item_id']][]=$row;
		}
		
		return $list;
	}
	
	private function makePages($name, $structuredList, $item, $deep=0)
	{
		$pages = array();
		$pages['label'] = $item['label'];
		
		
		
		if(!empty($item['uri']) || empty($item['route_name']))
		{
			$pages['uri'] = (string)$item['uri'];
			$current_uri = $this->getCurrentUri();
			if(trim($pages['uri'], '/') == trim($current_uri, '/')) $pages['active'] = 1;
		}
		else
		{
			$route = $item['route_name'];
			$params = !empty($item['route_params']) ? (array)json_decode($item['route_params']) : null;
			
			$auth = $this->sm->get('AuthorizationController');
			$routesModel = $this->sm->get('RoutesTable');
			$routerId = $routesModel->getRouterIdByRoute($route, $params);
			
			if($auth->isAllowed($routerId, 'view'))
			{
				if(!empty($params))
				{
					$pages['route'] = $route;
					$pages['params'] = $params;
				}
				else
				{
					$pages['route'] = $route;
				}
			}
			else return false;
		}
		
		$pages['pages'] = array();
		
		if(isset($structuredList[$name][$item['id']]))
		{
			foreach($structuredList[$name][$item['id']] as $i)
			{
				
				$child = $this->makePages($name, $structuredList, $i, $deep + 1);
				if($child) $pages['pages'][]=$child;
			}
		}
			
		return $pages;
			
	}
}