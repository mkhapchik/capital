<?php
namespace Categories\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IncomeController extends AbstractActionController
{
	public function indexAction()
	{
		//return array('test'=>);
			
		$this->layout()->setVariables(array(
			'title' => 'TEST',
		));
		
		$view = new ViewModel(array(
            'test' => "qwe",
        ));
		
		//$view->setTerminal(true);
 
        return $view;
	}
}
?>