<?php
return array(
	'router' => array(
		'routes' => array(
			'sistema' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/sistema',
					'defaults' => array(
						'controller' => 'Sistema\Portada',
						'action' => 'inicio'
					)
				),
				'may_terminate' => true,
				'child_routes' => array(
					'autenticacion' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '[/:action]',
							'constraints' => array(
								'action' => '(login|logout)'
							),
							'defaults' => array(
								'controller' => 'Sistema\Autenticacion'
							)
						)
					),
					'rol' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/rol[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|accesos)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Rol',
								'action' => 'inicio'
							)
						)
					),
					'persona' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/persona[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Persona',
								'action' => 'inicio'
							)
						)
					),
					'perusuario' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/perusuario[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|estado|rol|accesos|resetearAccesos)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Perusuario',
								'action' => 'inicio'
							)
						)
					),
					'titulo' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/titulo[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|editar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Titulo',
								'action' => 'inicio'
							)
						)
					),
					'contacto' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/contacto[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|editar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Contacto',
								'action' => 'inicio'
							)
						)
					),
					'slider' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/slider[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|publicar|ordenar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Slider',
								'action' => 'inicio'
							)
						)
					),
					'noticia' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/noticia[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|publicar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Noticia',
								'action' => 'inicio'
							)
						)
					),
					'publicacion' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/publicacion[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|publicar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Publicacion',
								'action' => 'inicio'
							)
						)
					),
					'galeria' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/galeria[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|ordenar|publicar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Galeria',
								'action' => 'inicio'
							)
						)
					),
					'imagengaleria' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/imagengaleria[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|ordenar|publicar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Imagengaleria',
								'action' => 'inicio'
							)
						)
					),
					'video' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/video[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|publicar|ordenar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Video',
								'action' => 'inicio'
							)
						)
					),
					'boletin' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/boletin[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|ordenar|publicar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Boletin',
								'action' => 'inicio'
							)
						)
					),
					'infoterritorial' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/infoterritorial[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|editar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Infoterritorial',
								'action' => 'inicio'
							)
						)
					),
					'datosespaciales' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/datosespaciales[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|ordenar|publicar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Datosespaciales',
								'action' => 'inicio'
							)
						)
					),
					'urlportal' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/urlportal[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|editar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Urlportal',
								'action' => 'inicio'
							)
						)
					),
					'publicaciones' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/publicaciones[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|editar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Publicaciones',
								'action' => 'inicio'
							)
						)
					),
					'tipocontenido' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/tipocontenido[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Tipocontenido',
								'action' => 'inicio'
							)
						)
					),
					'nivelmenu' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/nivelmenu[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Nivelmenu',
								'action' => 'inicio'
							)
						)
					),
					'menu' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/menu[/:action][/:parametros]',
							'constraints' => array(
								'pagina' => '[0-9]+',
								'action' => '(filtrar|agregar|editar|eliminar|ordenar|contenido|publicar|recargarPadre)',
								'id' => '[0-9]+'
							),
							'defaults' => array(
								'controller' => 'Sistema\Menu',
								'action' => 'inicio'
							)
						)
					)
				)
			),
			'portal' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/',
					'defaults' => array(
						'controller' => 'Portal\Portada',
						'action' => 'inicio'
					)
				),
				'may_terminate' => true,
				'child_routes' => array(
					'publicaciones' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'publicaciones[/:pagina]',
							'defaults' => array(
								'controller' => 'Portal\Publicacion',
								'action' => 'lista'
							)
						)
					),
					'contenido' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'contenido[/:codigo]',
							'defaults' => array(
								'controller' => 'Portal\Contenido',
								'action' => 'detalle'
							)
						)
					),
					'noticia' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'noticia[/:codigo]',
							'defaults' => array(
								'controller' => 'Portal\Noticia',
								'action' => 'detalle'
							)
						)
					),
					'noticias' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'noticias[/:pagina]',
							'defaults' => array(
								'controller' => 'Portal\Noticia',
								'action' => 'lista',
								'pagina' => 1
							)
						)
					),
					'galeria' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'galeria[/:codigo]',
							'defaults' => array(
								'controller' => 'Portal\Galeria',
								'action' => 'lista'
							)
						)
					),
					'boletines' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'boletines[/:pagina]',
							'defaults' => array(
								'controller' => 'Portal\Boletin',
								'action' => 'lista'
							)
						)
					),
					'mapaweb' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'mapaweb',
							'defaults' => array(
								'controller' => 'Portal\Mapaweb',
								'action' => 'inicio'
							)
						)
					),
					'contacto' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'contacto',
							'defaults' => array(
								'controller' => 'Portal\Contacto',
								'action' => 'inicio'
							)
						)
					),
					'datosespaciales' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'datosespaciales',
							'defaults' => array(
								'controller' => 'Portal\Datosespaciales',
								'action' => 'inicio'
							)
						)
					)
				)
			)
		)
	)
);