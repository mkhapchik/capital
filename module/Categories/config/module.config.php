<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'Categories\Controller\Income' => 'Categories\Controller\IncomeController'
        ),
    ),
	
	'router' => array(
        'routes' => array(
            'categories' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/categories',
					'defaults' => array(),
                ),
                'child_routes' => array(
					'income' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/income[/:action][/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
								'__NAMESPACE__' => 'Categories\Controller',
								'controller'    => 'Income',
								'action'        => 'index',
                            ),
                        ),
                    ),
                ),
				
            ),
        ),
    ),

	'view_manager' => array(
		'template_path_stack' => array(
			'categories' => __DIR__ . '/../view',
		)
	),
);