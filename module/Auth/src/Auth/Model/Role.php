<?php
namespace Auth\Model;

class Role
{
	public $id;
    public $name;
    public $label;
	public $is_guest;
		 
    public function exchangeArray($data)
    {
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->label = (isset($data['label'])) ? $data['label'] : null;
		$this->is_guest = (isset($data['is_guest'])) ? $data['is_guest'] : null;
    }
}