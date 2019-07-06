<?php
namespace HtImgModule\Factory;

use HtImgModule\Imagine\Resolver\ResolverManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResolverManagerFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$config = $serviceLocator->get('Config');
		$config = new Config($config['htimg']['resolvers_manager']);
		$resolverManager = new ResolverManager($config);
		$resolverManager->setServiceLocator($serviceLocator);

		return $resolverManager;
	}
}
