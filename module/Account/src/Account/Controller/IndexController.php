<?php
namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Account\Model\Account;
use Account\Model\AccountTable;
use PDO;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Account\Form\AccountForm;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		$accountTable = $this->getAccountTable();
		$accounts = $accountTable->fetchAll();
		
		$this->layout()->setVariable('title', 'Счета');
		
		$view = new ViewModel(array(
            'accounts' => $accounts,
        ));
		
        return $view;
    }
	
	public function addAction()
    {
       
		$form = new AccountForm();
		
        $form->get('submit')->setValue('Добавить');
 
        $request = $this->getRequest();
        if ($request->isPost()) 
		{
            $form->setData($request->getPost());
 
            if ($form->isValid()) 
			{
                $account = new Account();
				$account->exchangeArray($form->getData());
                $this->getAccountTable()->saveAccount($account);
 
                
				return $this->redirect()->toRoute('account/default');
            }
        }
		return array('form' => $form);
    }
	
	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$accountTable = $this->getAccountTable();
		$old_account = $accountTable->getAccount($id);
		
		$form = new AccountForm();
		$form->get('submit')->setValue('Сохранить');
		$form->setData((array)$old_account);
		
		$request = $this->getRequest();
        if ($request->isPost()) 
		{
            $form->setData($request->getPost());
 
            if ($form->isValid()) 
			{
                $account = new Account();
				$account->exchangeArray($form->getData());
                $accountTable->saveAccount($account);
                
				return $this->redirect()->toRoute('account/default');
            }
        }
		
		return array('form' => $form);
	}
	
	public function delAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$accountTable = $this->getAccountTable();
		$accountTable->deleteAccount($id);
		return $this->redirect()->toRoute('account/default');
	}
	
	
	
	private function getAccountTable()
	{
		$sm = $this->getServiceLocator();
		$accountTable = $sm->get('AccountTable');
		return $accountTable;
	}
}
