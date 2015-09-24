<?php
namespace Filemanager\Model;

class FilemanagerModel
{
	private $homeDir;
	private $documentRoot;
	private $homeLink;
	
	public function __construct()
	{
		$this->homeDir = $this->trim(realpath($_SERVER['DOCUMENT_ROOT'] . '/files'));
		$this->documentRoot = $this->trim(realpath($_SERVER['DOCUMENT_ROOT']));
		$this->homeLink = $this->trim(str_replace($this->documentRoot, '', $this->homeDir));
	}
	
	public function getList($link, $only_dir = false, $exten=false)
	{
		
		$filepath = $this->documentRoot . '/' . $link;
		
		$files = glob($filepath . "/*");
	
		$list = array();
		foreach($files as $file)
		{
			$is_dir = is_dir($file);
			if($only_dir && !$is_dir) continue;
				
			$pathinfo = pathinfo($file);
			$path = $link .'/'. $pathinfo['basename'];
			$file_ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
			
			if(!$is_dir && is_array($exten) && !in_array($file_ext, $exten)) continue;
			
			$list[$path] = $pathinfo;
			$list[$path]['is_dir'] = $is_dir;
			
			$img_exten = $this->getImgExtemsions();
			$is_img = ($file_ext && in_array($file_ext, $img_exten)) ? 1 : 0;
			$list[$path]['is_img'] = $is_img;
			
			$list[$path]['class'] = $is_dir ? 'dir' : 'file';
			if($is_img) $list[$path]['class'] .= " img";
			if($file_ext) $list[$path]['class'] .= " $file_ext";
			
			
			
		}
			
		return $list;
	}
	
	public function getImgExtemsions()
	{
		return array('jpg', 'jpeg', 'png', 'gif');
	}
	
	public function upload($from, $to, $name)
	{
		$to = $this->getFilePath($to);
		if(is_dir($to))
		{
			move_uploaded_file($from, $to . '/' . basename($name ));
		}
	}
	
	public function delete($link)
	{
		$path = $this->getFilePath($link);
		if(file_exists($path)) unlink($path);
	}
	
	public function reallink($link)
	{
		$filepath = $this->getFilePath($link);
		$canonical_link = str_replace($this->documentRoot, '', $filepath);
		if($canonical_link) $canonical_link = $this->trim($canonical_link);
		
		return $canonical_link;
	}
	
	public function getFilePath($link)
	{ 
		if(empty($link)) $link = $this->homeLink;
		
		$filepath = $this->trim(realpath($this->documentRoot . '/' . $link));
		return $filepath;
	}
	
	public function is_allowed($link, $allowed_links)
	{
		if($allowed_links===true || (is_array($allowed_links) && in_array($link, $allowed_links))) 
		{
			$allowed = true;
		}
		else 
		{
			$allowed = false;
		}

		return $allowed;
	}
	
	protected function trim($val)
	{
		return trim(str_replace('\\', '/', $val), '/');
	}
	
	
	
}
?>