<?php
namespace HtImgModule\Factory;

use HtImgModule\Options\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleOptionsFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$variable = $serviceLocator->get('Config');
		return new ModuleOptions($variable['htimg']);
	}
}
