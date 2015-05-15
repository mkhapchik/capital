<?php
namespace Categories\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Categories\Model\Category;
use Categories\Form\IncomeForm;

class IncomeController extends AbstractActionController
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
		$categoryTable->setType(1);
		$categories = $categoryTable->fetchAll();
				
		$this->layout()->setVariable('title', 'Категории дохода');
		
		$view = new ViewModel(array(
            'categories' => $categories,
        ));
		
        return $view;
		
	}
	
	public function addAction()
	{
		$form = new IncomeForm();
		
        $form->get('submit')->setValue('Добавить');
 
        $request = $this->getRequest();
        if ($request->isPost()) 
		{
            $form->setData($request->getPost());
 
            if ($form->isValid()) 
			{
                $category = new Category();
				$category->exchangeArray($form->getData());
                $categoryTable = $this->getCategoryTable();
				$categoryTable->setType(1);
				
				$categoryTable->saveCategory($category);
 
                
				return $this->redirect()->toRoute('categories/income');
            }
        }
		return array('form' => $form);
	}
	
	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$categoryTable = $this->getCategoryTable();
		$categoryTable->setType(1);
		$old_category = $categoryTable->getCategory($id);
		
		$form = new IncomeForm();
		$form->get('submit')->setValue('Сохранить');
		$form->setData((array)$old_category);
		
		$request = $this->getRequest();
        if ($request->isPost()) 
		{
            $form->setData($request->getPost());
 
            if ($form->isValid()) 
			{
                $category = new Category();
				$category->exchangeArray($form->getData());
                $categoryTable = $this->getCategoryTable();
				$categoryTable->setType(1);
				
				$categoryTable->saveCategory($category);
                
				return $this->redirect()->toRoute('categories/income');
            }
        }
		
		return array('form' => $form);
	}
	
	public function delAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$categoryTable = $this->getCategoryTable();
		$categoryTable->setType(1);
		$categoryTable->deleteCategory($id);
		return $this->redirect()->toRoute('categories/income');
	}
	
	private function getCategoryTable()
	{
		$sm = $this->getServiceLocator();
		$categoryTable = $sm->get('CategoryTable');
		return $categoryTable;
	}
	
}
?>