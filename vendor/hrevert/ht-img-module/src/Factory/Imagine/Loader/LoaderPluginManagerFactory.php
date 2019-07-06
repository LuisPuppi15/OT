<?php
namespace HtImgModule\Factory\Imagine\Loader;

use HtImgModule\Imagine\Loader\LoaderPluginManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoaderPluginManagerFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$config = $serviceLocator->get('Config');
		return new LoaderPluginManager(new Config($config['htimg']['loaders']));
	}
}
