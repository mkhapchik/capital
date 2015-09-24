<?php
namespace Filemanager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FilemanagerController extends AbstractActionController
{
	public function viewAction()
	{
		
	}
	
	public function listAction()
	{
		$dir = $this->getLinkFromPost('');
		$model = $this->getModel();		
		if($this->is_allowed($dir)) 
		{
			$list = $model->getList($dir);
			$message = '';
		}
		else 
		{
			$list = array();
			$message = 'Доступ запрещен';
		}
		
		$bread_crumbs = $this->getBreadCrumbsFromPath($dir);
		
		$view = new ViewModel(array('list' => $list, 'dir'=>$dir, 'message'=>$message, 'bread_crumbs'=>$bread_crumbs));
		$view->setTerminal(true);
        
		return $view;
	}
	
	protected function getBreadCrumbsFromPath($path)
	{
		$bc = array();
		$path = explode('/', $path);
		$link='';
		$l = count($path);
		for($i=0; $i<$l; $i++)
		{
			if($i==$l-1) $link='';
			else $link.= "/$path[$i]";
			$bc[$link]=$path[$i];
		}
		
		return $bc;
	}
	
	public function uploadAction()
	{
		$dir = $this->getLinkFromPost(false);
		
		if($dir && $this->is_allowed($dir))
		{
			$model = $this->getModel();
			
			foreach($_FILES as $file)
			{
				$model->upload($file['tmp_name'], $dir, $file['name']);
			}
			
			
		}
		exit();
	}
	
	public function deleteAction()
	{
		$files = $this->getFromPost('files', false);
		if(is_array($files))
		{
			$model = $this->getModel();
			foreach($files as $path)
			{				
				if($this->is_allowed(dirname($path)))
				{
					$link = $model->reallink($path);
					$model->delete($link);
				}
			}
		}
		exit();
	}
	
	protected function is_allowed($dir)
	{
		$model = $this->getModel();
		return $model->is_allowed($dir, array('files', 'files/dir1/ddd', 'files/dir1'));
	}
	
	protected function getModel()
	{
		$sm = $this->getServiceLocator();
		$m = $sm->get('Filemanager\Model\FilemanagerModel');
		return $m;
	}
	
	protected function getLinkFromPost($def_val='')
	{
		$model = $this->getModel();
		$path = $this->getFromPost('path', $def_val);
		$link = $model->reallink($path);
		
		return $link;
	}
	
	protected function getFromPost($name, $def_value=null)
	{
		$result = $def_value;
		$request = $this->getRequest();
		if($request->isPost()) 
		{
			$val = $request->getPost($name, $def_value);
			if($val) $result = $val;
		}
		
		return $result;
	}
}
?>