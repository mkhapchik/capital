<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'Transactions\Controller\TransactionIncome' => 'Transactions\Controller\TransactionIncomeController',	
        ),
    ),
	
	'router' => array(
        'routes' => array(
            'transactions' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/transactions',
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
								'__NAMESPACE__' => 'Transactions\Controller',
								'controller'    => 'TransactionIncome',
								'action'        => 'add',
                            ),
                        ),
                    ),
					'expense'=>array(
						'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/expense[/:action][/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
								'__NAMESPACE__' => 'Transactions\Controller',
								'controller'    => 'TransactionExpense',
								'action'        => 'add',
                            ),
                        ),
					)
                ),
				
            ),
        ),
    ),

	'view_manager' => array(
		'template_path_stack' => array(
			'transactions' => __DIR__ . '/../view',
		)
	),
);