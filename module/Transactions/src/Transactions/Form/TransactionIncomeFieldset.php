<?php
namespace Transactions\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
 
class TransactionIncomeFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($categories=false, $accounts=false)
    {
       parent::__construct('transaction_income');
	   if(!$categories) $categories=array();
	   if(!$accounts) $accounts = array();
	   
	   $this->categories = $categories;
	   $this->accounts = $accounts;
	   
       $this->init();
    }
	
	public function init()
	{
		/*
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		*/
		
		$this->add(array(
            'name' => 'date',
            'attributes' => array(
                'type'  => 'text',
				'class'=>'date',
				'value'=>date("d.m.Y"),
            ),
            'options' => array(
                'label' => 'Дата',
            ),
        ));
		
		$this->add(array(
            'name' => 'amount',
            'attributes' => array(
                'type'  => 'text',
				'class'=>'currency'
            ),
            'options' => array(
                'label' => 'Сумма',
            ),
        ));
		
		$this->add(array(
            'name' => 'categories_id',
            'type' => 'Select',
			'attributes' => array(
               //'value'=>3
            ),
            'options' => array(
                'label' => 'Категория',
				'value_options' => $this->categories,
				//'empty_option' => 'Please choose your language',
            ),
			
        ));
		
		$this->add(array(
            'name' => 'account_id',
            'type' => 'Select',
			'attributes' => array(
                
            ),
            'options' => array(
                'label' => 'Счет',
				'value_options' => $this->accounts
            ),
        )); 
		
		$this->add(array(
            'name' => 'comment',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Комментрарий',
            ),
        )); 
		
	}
 
    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
            )
        );
    }
}