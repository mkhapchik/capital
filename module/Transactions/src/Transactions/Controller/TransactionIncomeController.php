<?php
namespace Transactions\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Transactions\Form\TransactionIncomeForm;

class TransactionIncomeController extends AbstractActionController
{
	public function addAction()
	{
		$form = new TransactionIncomeForm();
		
		$categories = $this->getCategories();
		$form->setCategories($categories);
		$accounts = $this->getAccounts();
		$form->setAccounts($accounts);
		
		$form->init();
        $form->get('submit')->setValue('Сохранить');
 
        $request = $this->getRequest();
        if ($request->isPost()) 
		{
            $form->setData($request->getPost());
 
            if ($form->isValid()) 
			{
				/*
				$category = new Category();
				$category->exchangeArray($form->getData());
                $categoryTable = $this->getCategoryTable();
				$categoryTable->setType(1);
				
				$categoryTable->saveCategory($category);
 
                
				return $this->redirect()->toRoute('categories/income');
				*/
            }
        }
		return array('form' => $form);
	}
	
	private function getCategories()
	{
		$sm = $this->getServiceLocator();
		$categoryTable = $sm->get('CategoryTable');
		$categoryTable->setType(1);
		$categories = $categoryTable->getGuide();
		$result = array();
		if($categories)
		{
			
			foreach($categories as $category)
			{
				$result[$category['id']] = $category['name'];
			}
		}
		
		return $result;
	}
	
	private function getAccounts()
	{
		$sm = $this->getServiceLocator();
		$accountTable = $sm->get('AccountTable');
		$accounts = $accountTable->getGuide();
		$result = array();
		if($accounts)
		{
			foreach($accounts as $account)
			{
				$result[$account['id']]=$account['name'];
			}
		}
		return $result;
	}
}