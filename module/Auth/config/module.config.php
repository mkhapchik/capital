<?php
return array(
	'auth'=>array(
		'max_counter_failures'=>3,
		'success_login_redirect_router'=>'home',
		'logout_redirect_router'=>'home',
		'inactivity_time_min' => 0.1,
		'frequency_of_check_timeout_sec'=>5
	),	
	'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Authentication' => 'Auth\Controller\AuthenticationController',	
			'Auth\Controller\Authorization' => 'Auth\Controller\AuthorizationController',	
        ),
    ),
	'view_helpers' => array(
        'invokables' => array(
            'AuthHelper' => 'Auth\View\Helper\AuthHelper',
        )
    ),
	'router' => array(
        'routes' => array(
			'auth' => array(
				'type'    => 'Literal',
                'options' => array(
                    'route'    => '/auth',
					'defaults' => array(),
                ),
                'child_routes' => array(
					'login' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/login',
                            'defaults' => array(
								'__NAMESPACE__' => 'Auth\Controller',
								'controller'    => 'Authentication',
								'action'        => 'login',
                            ),
                        ),						
                    ),
					'logout'=> array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/logout',
                            'defaults' => array(
								'__NAMESPACE__' => 'Auth\Controller',
								'controller'    => 'Authentication',
								'action'        => 'logout',
                            ),
                        ),						
                    ),
					'timeout'=>array(
						'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/timeout',
                            'defaults' => array(
								'__NAMESPACE__' => 'Auth\Controller',
								'controller'    => 'Authorization',
								'action'        => 'checkTimeout',
                            ),
                        ),	
					)
					
                ),
			),	
        ),
    ),

	'view_manager' => array(
		'template_path_stack' => array(
			'auth' => __DIR__ . '/../view',
		)
	),
);