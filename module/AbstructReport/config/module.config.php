<?php
return array(
	'report'=>array(
		'table'=>array(
			'countPerPage'=>10
		)
	),
	'controllers' => array(
        'invokables' => array(
            //'Settings\Controller\Settings' => 'Settings\Controller\SettingsController'
        ),
    ),
	'view_manager' => array(
		'template_path_stack' => array(
			'report' => __DIR__ . '/../view',
		)
	),
);