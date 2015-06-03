<?php
namespace Transactions\Form;
 
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;

 
class TransactionIncomeForm extends Form
{
    private $type;
	private $categories;
	
	/**
	* Конструктор
	* @param $type - тип транзакции 1 - доход, 0 - расход
	* @param $name - имя формы
	*/
	public function __construct($name = 'transaction')
    {
        parent::__construct($name);
		
		//$this->type = $type ? 1 : 0;
		$this->categories = array();
		$this->accounts = array();
    }
	
	public function init($count=1)
	{
		
		$this->setAttribute('method', 'post');
        /*  
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
		$this->add(array(
            'name' => 'date',
            'attributes' => array(
                'type'  => 'text',
				'class'=>'date'
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
		*/

		$this->add(array(
            'name' => 'transaction',
			'type' => 'Zend\Form\Element\Collection',
            'options' => array(
                'use_as_base_fieldset' => true,
				'count' => $count,
                'should_create_template' => true,
                'allow_add' => true,
				/*
				'target_element' => array(
                    'type' => 'Transactions\Form\TransactionIncomeFieldset'
                )
				*/
				'target_element' => new \Transactions\Form\TransactionIncomeFieldset($this->categories, $this->accounts)
            )
        ));
 
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Сохранить',
                'id' => 'submitbutton',
            ),
        ));
		
		$inputFilter = $this->__getInputFilter();
		$this->setInputFilter($inputFilter);
	}
	
	private function __getInputFilter()
	{
		
		$inputFilter = new InputFilter();
		$factory     = new InputFactory();

		/*
		$inputFilter->add($factory->createInput(array(
			'name'     => 'id',
			'required' => true,
			'filters'  => array(
				array('name' => 'Int'),
			),
		)));

		$inputFilter->add($factory->createInput(array(
			'name'     => 'name',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                               //NotEmpty::IS_EMPTY => 'qqq' 
                            ),
                        ),
				),
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 1,
						'max'      => 20,
						'messages' => array(
							//StringLength::TOO_LONG => 'qwe %min% %max%'
						)
					),
				),
				
			),
		)));
		*/
		
		return $inputFilter;
	}
	
	public function setCategories($categories)
	{
		$this->categories = $categories;
	}
	
	public function setAccounts($accounts)
	{
		$this->accounts = $accounts;
		
	}
	
}