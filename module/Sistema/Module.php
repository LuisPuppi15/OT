<?php
namespace Sistema;
session_start();

use Sistema\Listener\AutenticacionListener;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {
	public function getConfig() {
		$config = array();
		$configFiles = array(
			__DIR__ . '/config/module.config.php',
			__DIR__ . '/config/module.config.routes.php'
		);

		foreach ($configFiles as $configFile) {
			$config = \Zend\Stdlib\ArrayUtils::merge($config, include $configFile);
		}

		return $config;
	}

	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__=> __DIR__ . '/src/' . __NAMESPACE__
				)
			)
		);
	}

	public function getServiceConfig() {
		return array(
			'factories' => array(
				'Doctrine\AuthenticationService' => function ($serviceManager) {
					return $serviceManager->get('doctrine.authenticationadapter.orm_default');
				}
			)
		);
	}

	public function onBootstrap(MvcEvent $e) {
		$eventManager = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);

		// Autenticación
		$authListener = new AutenticacionListener();
		$eventManager->attachAggregate($authListener);

		// Layouts
		$eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function ($e) {
			$controller = $e->getTarget();
			$controllerClass = get_class($controller);
			$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
			$config = $e->getApplication()->getServiceManager()->get('config');

			if (isset($config['module_layouts'][$moduleNamespace])) {
				$controller->layout($config['module_layouts'][$moduleNamespace]);
			}
		}, 100);
	}
}
