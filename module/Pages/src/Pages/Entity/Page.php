<?php
namespace Pages\Entity;

class Page
{
	public $id;
	
	/**
	*  системное имя
	*/
	public $name;
	
	/**
	* название
	*/
	public $label;
	
	/**
	* имя маршрута
	*/
	public $route_name;
	
	/**
	* параметры маршрута
	*/
	public $route_params;
	
	/**
	* флаг активности страницы
	*/
	public $is_active=0;
	
	/**
	* флаг удаления страницы
	*/
	public $is_delete=1;
	
	/**
	* Title страницы
	*/
	public $title;
	
	/**
	*  Заголовок страницы
	*/
	public $header;
	
	/**
	* Основное содержимое, html текст
	*/
	public $content;
	
	/**
	* автор создания страницы
	*/
	public $author_id;
	
	/**
	* дата создания
	*/
	public $date_creation;
	
	/**
	* дата последнего изменения
	*/
	public $date_last_modification;
	
	/**
	* является ли страница системной
	*/
	public $is_system;
	
	public function __construct()
	{
		
	}
	
	public function exchangeArray($data)
    {
		$class = get_class($this);
		foreach($data as $k=>$v) 
		{
			if(property_exists($class, $k))
			{
				switch($k)
				{
					case 'route_params' : 
						$this->route_params = unserialize($v); 
						break;
						
					default:
						$this->$k = $v;
				}
			}
		}
	}
	
	public function __get($name) 
	{
        return property_exists(get_class($this), $name) ? $this->$name : null;
    }
	
	public function getArrayCopy()
	{
		return (array)$this;
	}
}
	