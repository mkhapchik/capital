<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;

class AuthenticationController extends AbstractActionController
{
	public function loginAction()
	{
		$form = new LoginForm();
		$form->setAttribute('action', '/auth/login');
   
        $request = $this->getRequest();
        if ($request->isPost()) 
		{
            $form->setData($request->getPost());
 
            if ($form->isValid()) 
			{
    
                
				//return $this->redirect()->toRoute('categories/income');
            }
			else
			{
				//return $this->redirect()->toRoute('/auth/login/err/1');
			}
        }
		return array('form' => $form);
				
		//return array('form' => $form, 'is_success'=>$is_success, 'message'=>$message);
	}
}