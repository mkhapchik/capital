<?php
namespace Transactions\Entity;

class Transaction
{
	public $id;
	
	/**
	* ���� ��������
	*/
	public $date;
	
	/**
	* ����� ��������
	*/
	public $amount;
	
	/**
	* id ���������
	*/
	public $categories_id;
	
	/**
	* id �����
	*/
	public $account_id;
	
	/**
	* ������������
	*/
	public $comment;
	
	/**
	* 1 - �����, -1 - ������
	*/	
	public function exchangeArray($data)
	{
		foreach($data as $var=>$value) $this->$var = $value;
		if(!array_key_exists('id', $data)) $this->id = false;
	}
}
?>