<?php
return array(
	'htimg' => array(
		'filters' => array(// Just one for quick and easy way
			'thumbnail' => array(
				'type' => 'thumbnail',
				'options' => array(
					'width' => 100,
					'height' => 100
				)
			)
		),
		'image_resolvers' => array(
			1000 => 'image_map',
			200 => 'image_path_stack'
		),
		'resolvers_manager' => array(),
		'loaders' => array()
	),
	'router' => array(
		'routes' => array(
			'htimg' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/htimg'
				),
				'may_terminate' => true,
				'child_routes' => array(
					'display' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/display/:filter/',
							'defaults' => array(
								'controller' => 'htimg',
								'action' => 'display'
							)
						)
					)
				)
			)
		)
	),
	'controllers' => array(
		'factories' => array(
			'htimg' => 'HtImgModule\Controller\Factory\ImageControllerFactory'
		)
	),
	'view_manager' => array(
		'strategies' => array(
			'HtImgModule\View\Strategy\ImageStrategy'
		)
	)
);
