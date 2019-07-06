<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
	'db' => array(
		'driver' => 'pgsql',
		'host' => '172.16.0.152',
		'port' => '5432',
		'user' => 'postgres',
		'password' => 'muchick2010',
		'dbname' => 'otsgrl2015'
	),
	'service_manager' => array(
		'factories' => array(
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		)
	),
	'module_layouts' => array(
		// 'Application' => 'layout/layout',
		'Sistema' => 'sistema\layout',
		'Portal' => 'portal\layout'
	)
);
