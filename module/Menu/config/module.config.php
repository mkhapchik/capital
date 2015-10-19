<?php
return array(
	'controllers' => array(
        'invokables' => array(
			'Menu\Controller\Menu'=>'Menu\Controller\MenuController'
        ),
    ),
	
	'router' => array(
        'routes' => array(
			'menu_admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin/menu',
					'defaults' => array(),
                ),
				'child_routes' => array(
					'list_menu' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/list[/]',
                            'constraints' => array(),
                            'defaults' => array(
								'__NAMESPACE__' => 'Menu\Controller',
								'controller'    => 'Menu',
								'action' => 'list'
                            ),
                        ),
                    ),
					'active_toggle'=> array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/active_toggle[/:id][/]',
                            'constraints' => array(),
                            'defaults' => array(
								'__NAMESPACE__' => 'Menu\Controller',
								'controller'    => 'Menu',
								'action' => 'activeToggle'
                            ),
                        ),
                    ),
					/*
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
					*/
                ),
			),
        ),
    ),

	'view_manager' => array(
		'template_path_stack' => array(
			'menu' => __DIR__ . '/../view',
		)
	),
	
);