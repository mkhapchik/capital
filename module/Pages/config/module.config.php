<?php
return array(
	'navigation'=>array(
		'admin'=>array(
			array(
				'label'=>'Страницы',
				'route'=>'pages_admin/list',
				'params'=>array()
			),
		)
	),
	
	'controllers' => array(
        'invokables' => array(
			'Pages\Controller\Page'=>'Pages\Controller\PageController'
        ),
    ),
	
	'router' => array(
        'routes' => array(
			'pages' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/pages[/:id][/]',
                    'constraints' => array(
						//'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Pages\Controller',
						'controller'    => 'Page',
						'action' => 'view'
					),
                ),
            ),
			'pages_admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin/pages',
					'defaults' => array(),
                ),
				'child_routes' => array(
					'list' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/list[/]',
                            'constraints' => array(),
                            'defaults' => array(
								'__NAMESPACE__' => 'Pages\Controller',
								'controller'    => 'Page',
								'action' => 'list'
                            ),
                        ),
                    ),
					
					'add'=>array(
						'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/add[/]',
                            'constraints' => array(),
                            'defaults' => array(
								'__NAMESPACE__' => 'Pages\Controller',
								'controller'    => 'Page',
								'action' => 'add'
                            ),
                        ),
					)
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