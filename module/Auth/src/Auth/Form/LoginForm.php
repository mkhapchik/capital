<?php
namespace Auth\Form;
 
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;

 
class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');
       
		$this->add(array(
            'name' => 'login',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Логин',
            ),
        ));
		
		$this->add(array(
            'name' => 'pwd',
            'attributes' => array(
                'type'  => 'password',
            ),
			'options' => array(
                'label' => 'Пароль',
            ),
        ));
		
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Войти',
            ),
        ));
		
		$inputFilter = $this->__getInputFilter();
		$this->setInputFilter($inputFilter);
    }
	
	private function __getInputFilter()
	{
		$inputFilter = new InputFilter();
		$factory     = new InputFactory();

		$inputFilter->add($factory->createInput(array(
			'name'     => 'login',
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
                               //NotEmpty::IS_EMPTY => 'Поле обязательно для заполнения' 
                            ),
                        ),
				),
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max'      => 50,
						'messages' => array(
							//StringLength::TOO_LONG => 'qwe %min% %max%'
						)
					),
				),
				
			),
		)));
		
		$inputFilter->add($factory->createInput(array(
			'name'     => 'pwd',
			'required' => true,
			'validators' => array(
				array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                               //NotEmpty::IS_EMPTY => 'Поле обязательно для заполнения' 
                            ),
                        ),
				),
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max'      => 50,
						'messages' => array(
							//StringLength::TOO_LONG => 'qwe %min% %max%'
						)
					),
				),
				
			),
		)));

		return $inputFilter;
	}
	
}