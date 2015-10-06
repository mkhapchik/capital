<?php
return array(
	'controllers' => array(
        'invokables' => array(
			'Pages\Controller\Index'=>'Pages\Controller\IndexController'
        ),
    ),
	
	'router' => array(
        'routes' => array(
            
			'pages' => array(
                'type'    => 'Segment',
                'options' => array(
					'route'    => '/pages[/][:action]',
					'constraints' => array(
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Pages\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
					),
				),
			),
			
        ),
    ),

	'view_manager' => array(
		'template_path_stack' => array(
			'pages' => __DIR__ . '/../view',
		)
	),
	/*
	'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'pages' => __DIR__ . '/../view',
        ),
    ),
	*/
);