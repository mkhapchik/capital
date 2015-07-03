<?php
namespace Reports\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TableCategoriesExpenController extends AbstractActionController
{	
	public function indexAction()
	{
		$categoryTable = $this->getCategoryTable();
		$categoryTable->setType(0);
		$report_table = array();
		
		$params = $this->params()->fromQuery();
		$start 	= isset($params['start']) && !empty($params['start']) 	? 	strtotime($params['start']) : 	strtotime(date('Y-m-1') . "- 1 month");
		$end 	= isset($params['end'] 	) && !empty($params['end'])		?	strtotime($params['end'])	:	strtotime("now");
				
		$year_start = date('Y', $start);
		$month_start = date('n', $start);
		$day_start = date('d', $start);
		
		$year_end = date('Y', $end);
		$month_end = date('n', $end);
		$day_end = date('d', $end);
		
		for($y=$year_start; $y<=$year_end; $y++)
		{
			if($y==$year_start) $m_start = $month_start;
			else $m_start = 1;
			
			if($y==$year_end) $m_end = $month_end;
			else $m_end = 12;
			
			for($m=$m_start; $m<=$m_end; $m++)
			{
				if($y==$year_start && $m==$month_start) $day = $day_start;
				else if($y==$year_end && $m==$month_end) $day = $day_end;
				else $day ='01';
				
				$report_table[$y][$m] = $categoryTable->fetchAll("$y-$m-$day");
			}
		}
		
		return array('report_table'=>$report_table, 'start'=>date('d.m.Y',$start), 'end'=>date('d.m.Y',$end));
		
	}
	
	private function getCategoryTable()
	{
		$sm = $this->getServiceLocator();
		$categoryTable = $sm->get('CategoryTable');
		return $categoryTable;
	}
}