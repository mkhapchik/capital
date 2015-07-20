<?php
return array(
	'max_counter_failures'=>3,
	
	'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Authentication' => 'Auth\Controller\AuthenticationController',	
        ),
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