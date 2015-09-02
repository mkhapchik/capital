<?php
namespace Auth\Form;
 
//use Zend\Form\Form;
use Application\Form\CaptchaForm;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;

 
//class LoginForm extends Form
class LoginForm extends CaptchaForm
{
    /*
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
			'type' => 'Captcha',
			'name' => 'captcha',
			'options' => array(
				'label' => 'Введите код с картинки',
				'captcha' => array(
                    //'class' => 'Dumb',
					'class' => 'Image',
					'font'=>'./data/captcha/font/arial.ttf',
					'imgUrl'=>'/img/captcha/',
					'imgDir'=>'public/img/captcha/',
					//'gcFreq'=>'1',
                    //'expiration'=>'1',
                    'wordlen'=>'5',
                    //'timeout'=>'1',
                    'keepSession'=>false,
					'width'=>120,
					'height' => 50,
					'fontSize'=>20,
					'dotNoiseLevel' => 80,
					//'lineNoiseLevel' => 0
					//'class'=>'Figlet'
					'messages' => array(
						\Zend\Captcha\AbstractWord::BAD_CAPTCHA => "",
						\Zend\Captcha\AbstractWord::MISSING_VALUE => "",
						\Zend\Captcha\AbstractWord::MISSING_ID => "",
					),
                ),
			)
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
	*/
	protected function initForm()
	{
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
		
	}
	
	protected function __getInputFilter()
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
							//StringLength::TOO_LONG => 'Длина логина не должна превышать %max% символов'
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
							//StringLength::TOO_LONG => 'Длина пароля не должна превышать %max% символов'
						)
					),
				),
				
			),
		)));
		
		return $inputFilter;
	}
	
}