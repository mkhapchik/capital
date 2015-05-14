<?php
namespace Categories\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Categories\Model\CategoryTable;

class ExpenseController extends AbstractActionController
{
	public function indexAction()
	{
		/*
			
		$this->layout()->setVariables(array(
			'title' => 'TEST',
		));
		
		$view = new ViewModel(array(
            'test' => "qwe",
        ));
		
		//$view->setTerminal(true);
 
        return $view;
		*/
		$categoryTable = $this->getCategoryTable();
		$categoryTable->setType(0);
		$categories = $categoryTable->fetchAll();
				
		//$accounts = $accountTable->getAccount(1);
		
		$this->layout()->setVariable('title', 'Категории расхода');
		
		$view = new ViewModel(array(
            'categories' => $categories,
        ));
		
        return $view;
		
	}
	
	public function addAction()
	{
		$view = new ViewModel(array(
            'test' => "qwe",
        ));
		
		return $view;
	}
	
	private function getCategoryTable()
	{
		$sm = $this->getServiceLocator();
		$categoryTable = $sm->get('CategoryTable');
		return $categoryTable;
	}
	
}
?>