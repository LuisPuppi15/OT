<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Portal\Portada' => 'Portal\Controller\PortadaController',
			'Portal\Publicacion' => 'Portal\Controller\PublicacionController',
			'Portal\Contenido' => 'Portal\Controller\ContenidoController',
			'Portal\Noticia' => 'Portal\Controller\NoticiaController',
			'Portal\Galeria' => 'Portal\Controller\GaleriaController',
			'Portal\Boletin' => 'Portal\Controller\BoletinController',
			'Portal\Mapaweb' => 'Portal\Controller\MapawebController',
			'Portal\Contacto' => 'Portal\Controller\ContactoController',
			'Portal\Datosespaciales' => 'Portal\Controller\DatosespacialesController'
		)
	),
	'view_manager' => array(
		'template_map' => array(
			'portal\layout' => __DIR__ . '/../view/portal/layout.phtml',
			'portal\paginador' => __DIR__ . '/../view/portal/paginador.phtml'
		),
		'template_path_stack' => array(
			'portal' => __DIR__ . '/../view'
		),
		'strategies' => array(
			'ViewJsonStrategy'
		)
	),
	'view_helpers' => array(
		'invokables' => array(
			'fecha_helper' => 'Portal\View\Helper\FechaHelper',
			'menu_helper' => 'Portal\View\Helper\MenuHelper',
			'titulo_helper' => 'Portal\View\Helper\TituloHelper',
			'menu_atributos_helper' => 'Portal\View\Helper\MenuAtributosHelper',
			'menu_lateral_helper' => 'Portal\View\Helper\MenuLateralHelper',
			'menu_hijos_helper' => 'Portal\View\Helper\MenuHijosHelper',
			'mas_noticias_helper' => 'Portal\View\Helper\MasNoticiasHelper',
			'contacto_helper' => 'Portal\View\Helper\ContactoHelper',
			'galeria_helper' => 'Portal\View\Helper\GaleriaHelper',
			'boletin_helper' => 'Portal\View\Helper\BoletinHelper'
		)
	),
	'asset_manager' => array(
		'resolver_configs' => array(
			'paths' => array(
				__DIR__ . '/../public'
			)
		)
	)
);