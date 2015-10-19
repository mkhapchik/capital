<?php
namespace Pages\Controller;

use Zend\View\Model\ViewModel;
use AbstructReport\Controller\TableController;
class PageController extends TableController
{
	public function viewAction()
	{
		$pageId = (int) $this->params()->fromRoute('id', 0);
		$pageModel = $this->serviceLocator->get('Pages\Model\PageModel');
		$page = $pageModel->getPageById($pageId);
		$this->layout()->setVariable('title', $page->title);
		return array('page'=>$page);
	}
	
	public function listAction()
	{
		$params = $this->view();
		$this->layout()->setVariable('title', "Управление страницами");
		return new ViewModel($params);
	}
	
	protected function getPaginator($sort, $filter)
	{
		$pageModel = $this->serviceLocator->get('Pages\Model\PageModel');
		$paginator = $pageModel->fetchAll(array('is_delete'=>0, 'is_system'=>0), true, $sort, $filter);
		
		return $paginator;
	}

	protected function getSortList()
	{
		return array('header', 'date_last_modification', 'date_creation', 'author_id');
	}
	
	protected function getFilterList()
	{
		return array('header', 'author_id');
	}
	
	
}
