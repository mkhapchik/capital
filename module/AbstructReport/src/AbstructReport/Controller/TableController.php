<?php
namespace AbstructReport\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TableController extends AbstractActionController
{
    protected function view($params=array(), $f_filter=null, $countPerPage=false)
	{
		if(!$countPerPage)
		{
			$config = $this->serviceLocator->get('config');
			$countPerPage = (int)$config['report']['table']['countPerPage'];
		}
		else
		{
			$countPerPage = (int)$countPerPage;
		}
		
		if($f_filter) 
		{
			$filter = $this->params()->fromQuery('filter', $this->getDefaultFilter());
			if(!is_array($filter)) $filter = array();
			$filter = $filter+$this->getDefaultFilter();
			
		}
		else $filter=null;
			
		if(!isset($params['routName'])) $params['routName'] = $this->getEvent()->getRouteMatch()->getMatchedRouteName();
		if(!isset($params['routeParams'])) $params['routeParams'] = array('action' => 'view');
		if(!isset($params['routeOptions'])) $params['routeOptions'] = array();
		
		if(is_array($filter) && count($filter)>0) foreach($filter as $k=>$v) $params['routeOptions']['query']["filter[$k]"]=$v;	

		
		$paginator = $this->getPaginator($filter);
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
		$paginator->setItemCountPerPage($countPerPage);
			
		$filterForm = $this->getFilterForm();
		if($filterForm) $filterForm->initForm($filter);
				
		$view_params = array(
			'paginator' => $paginator,
			'filterForm'=>$filterForm,
		);
		
		$view_params = array_merge_recursive($view_params, $params);
		
		$view = new ViewModel($view_params);
		
		return $view; 
	}

	protected function getPaginator($filter)
	{
		die('function '  . __FUNCTION__  . 'is not exist');
	}
	
	protected function getDefaultFilter()
	{
		return null;
	}
	
	protected function getFilterForm()
	{
		return null;
	}
}
