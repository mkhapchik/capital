<?php
namespace Report\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormattingHelper extends AbstractHelper
{
	public function __invoke()
	{
		return $this;
	}
	
	public function formatting_number($val, $unit='', $base=1000)
	{
		if(empty($val)) return $val;
		$e = log($val, $base);
		$suffixes = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');   
		return round(pow($base, $e - floor($e)), 2) . ' '.$suffixes[floor($e)] . $unit;
	}
}
?>