<?php
namespace AbstructReport\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

Abstract class TableController extends AbstractActionController
{
    protected $routName;
	protected $routeParams;
	protected $routeOptions;
	
	protected function view()
	{
		$this->routName   = $this->getEvent()->getRouteMatch()->getMatchedRouteName();
		$this->routeParams = array();
		$this->routeOptions = array();
		$this->routeOptions['query'] = $this->params()->fromQuery();
		
		$sort = $this->clearSort($this->params()->fromQuery('sort', array()));
		$filter = $this->clearFilter($this->params()->fromQuery('filter', null));
				
		$paginator = $this->getPaginator($sort, $filter);
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
		$paginator->setItemCountPerPage((int) $this->params()->fromQuery('count', 50));

		$sortLinks = $this->getSortLinks($this->routeOptions, $this->routName, $this->routeParams);
		
		return array(
			'paginator' => $paginator,
			'routName' => $this->routName,
			'routeParams'=>$this->routeParams,
			'routeOptions'=>$this->routeOptions,
			'sortLinks'=>$sortLinks
		);
	}
	
	abstract protected function getPaginator($sort, $filter);
	
	protected function getSortList()
	{
		return array();
	}
	
	protected function getFilterList()
	{
		return array();
	}
	
	protected function clearSort($querySort)
	{
		$list = $this->getSortList();
		if(is_array($list) && count($list)>0 && is_array($querySort))
		{
			return array_filter($querySort, function($var, $key) use ($list){
				if(in_array($key, $list) && in_array($var, array('asc', 'desc'))) return true;
				else return array();
			}, ARRAY_FILTER_USE_BOTH);
		}
		else
		{
			return array();
		}
	}
	
	protected function clearFilter($queryFilter)
	{
		$list = $this->getFilterList();
		if(is_array($list) && count($list)>0 && is_array($queryFilter))
		{
			return array_filter($queryFilter, function($var, $key) use ($list){
				if(in_array($key, $list))
				{
					if(is_array($var))
					{
						return null;
					}
					else return !empty($var);
				}
				else 
				{
					return null;
				}
			}, ARRAY_FILTER_USE_BOTH);
		}
		else
		{
			return null;
		}
	}
	
	private function getSortLinks($options, $routName, $routeParams)
	{
		$list = $this->getSortList();
		if(is_array($list) && count($list)>0)
		{
			$sort_url = array();
			foreach($list as $name) $sort_url[$name] = $this->getSortItem($name, $options, $routName, $routeParams);
			
			return $sort_url;
		}
		else
		{
			return array();
		}
	}
	
	private function getSortItem($name, $options, $routName, $routeParams)
	{
		if(isset($options['query']['sort'][$name])) 
		{
			if($options['query']['sort'][$name]=='asc') 
			{
				$options['query']['sort'][$name] = 'desc';
				$dir = 'asc';
			}
			else
			{
				unset($options['query']['sort'][$name]);
				$dir = 'desc';
			}
		}
		else 
		{
			$options['query']['sort'][$name] = 'asc';
			$dir = '';
		}
		
		$viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
		$url = $viewHelperManager->get('url');
		
		return array(
			'url' => $url($routName, $routeParams, $options),
			'dir' => $dir,
		);
	}
	
}
