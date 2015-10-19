<?php
namespace Menu\Controller;

use Zend\View\Model\ViewModel;
use AbstructReport\Controller\TableController;

class MenuController extends TableController
{
	public function activeToggle()
	{
		$is_success = 0;
		$message = '';
		
		try
		{
			$menuModel = $this->getModel();
		
		
			//$status
		}
		catch(Exception $e)
		{
			$message=$e->getMessage();	
			$is_success = 0;
		}
		
		$params = array('is_success'=>$is_success, 'message'=>$message, 'status'=>$status);
		echo json_encode($params);
		exit();
	}
	
	public function listAction()
	{
		$params = $this->view();
		$this->layout()->setVariable('title', "Управление меню");
		return new ViewModel($params);
	}
	
	protected function getPaginator($sort, $filter)
	{
		$menuModel = $this->getModel();
		$paginator = $menuModel->fetchAll(null, true, $sort, $filter);
		
		return $paginator;
	}
	
	private function getModel()
	{
		return $this->serviceLocator->get('Menu\Model\MenuTable');
	}
	
	protected function getSortList()
	{
		return array('label', 'is_active');
	}
	/*
	protected function getFilterList()
	{
		return array('header', 'author_id');
	}
	*/
	
}
