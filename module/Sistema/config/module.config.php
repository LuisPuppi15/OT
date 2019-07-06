<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Sistema\Autenticacion' => 'Sistema\Controller\AutenticacionController',
			'Sistema\Portada' => 'Sistema\Controller\PortadaController',
			'Sistema\Persona' => 'Sistema\Controller\PersonaController',
			'Sistema\Rol' => 'Sistema\Controller\RolController',
			'Sistema\Perusuario' => 'Sistema\Controller\PerusuarioController',
			'Sistema\Contacto' => 'Sistema\Controller\ContactoController',
			'Sistema\Titulo' => 'Sistema\Controller\TituloController',
			'Sistema\Slider' => 'Sistema\Controller\SliderController',
			'Sistema\Noticia' => 'Sistema\Controller\NoticiaController',
			'Sistema\Publicacion' => 'Sistema\Controller\PublicacionController',
			'Sistema\Galeria' => 'Sistema\Controller\GaleriaController',
			'Sistema\Imagengaleria' => 'Sistema\Controller\ImagengaleriaController',
			'Sistema\Video' => 'Sistema\Controller\VideoController',
			'Sistema\Boletin' => 'Sistema\Controller\BoletinController',
			'Sistema\Tipocontenido' => 'Sistema\Controller\TipocontenidoController',
			'Sistema\Nivelmenu' => 'Sistema\Controller\NivelmenuController',
			'Sistema\Menu' => 'Sistema\Controller\MenuController',
			'Sistema\Urlportal' => 'Sistema\Controller\UrlportalController',
			'Sistema\Infoterritorial' => 'Sistema\Controller\InfoterritorialController',
			'Sistema\Datosespaciales' => 'Sistema\Controller\DatosespacialesController',
			'Sistema\Publicaciones' => 'Sistema\Controller\PublicacionesController'
		)
	),
	'view_manager' => array(
		'template_map' => array(
			'sistema\layout' => __DIR__ . '/../view/sistema/layout.phtml',
			'sistema\paginador' => __DIR__ . '/../view/layout/paginador.phtml'
		),
		'template_path_stack' => array(
			'sistema' => __DIR__ . '/../view'
		),
		'strategies' => array(
			'ViewJsonStrategy'
		)
	),
	'doctrine' => array(
		'driver' => array(
			'sistema\entities' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/Sistema/Entity')
			),
			'sistema\repositories' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/Sistema/Repository')
			),
			'orm_default' => array(
				'drivers' => array(
					'Sistema\Entity' => 'sistema\entities',
					'Sistema\Repository' => 'sistema\repositories'
				)
			)
		),
		'authentication' => array(
			'orm_default' => array(
				'object_manager' => 'Doctrine\ORM\EntityManager',
				'identity_class' => 'Sistema\Entity\Perusuario',
				'identity_property' => 'cperusuname',
				'credential_property' => 'cperusuclave'
			)
		),
		'authenticationadapter' => array(
			'orm_default' => true
		),

		'authenticationstorage' => array(
			'orm_default' => true
		),

		'authenticationservice' => array(
			'orm_default' => true
		)
	),
	'view_helpers' => array(
		'invokables' => array(
			'menu_estado_helper' => 'Sistema\View\Helper\MenuEstadoHelper',
			'control_hijos_helper' => 'Sistema\View\Helper\ControlHijosHelper'
		)
	),
	'asset_manager' => array(
		'resolver_configs' => array(
			'paths' => array(
				__DIR__ . '/../public'
			)
		)
	),
	'htimg' => array(
		'img_source_path_stack' => array(
			'module/Sistema/public/upload/img'
		),
		'filters' => array(
			'estandar' => array(
				'type' => 'thumbnail',
				'options' => array(
					'width' => 300,
					'height' => 180,
					'format' => 'jpeg'
				)
			),
			'publicacion' => array(
				'type' => 'thumbnail',
				'options' => array(
					'width' => 206,
					'height' => 256,
					'format' => 'jpeg'
				)
			),
			'galeria' => array(
				'type' => 'thumbnail',
				'options' => array(
					'width' => 220,
					'height' => 220,
					'format' => 'jpeg'
				)
			),
			'imagengaleria' => array(
				'type' => 'thumbnail',
				'options' => array(
					'width' => 314,
					'height' => 324,
					'format' => 'jpeg'
				)
			),
			'boletin-portada' => array(
				'type' => 'thumbnail',
				'options' => array(
					'width' => 276,
					'height' => 384,
					'format' => 'jpeg'
				)
			),
			'boletin' => array(
				'type' => 'thumbnail',
				'options' => array(
					'width' => 206,
					'height' => 256,
					'format' => 'jpeg'
				)
			)
		)
	)
);
