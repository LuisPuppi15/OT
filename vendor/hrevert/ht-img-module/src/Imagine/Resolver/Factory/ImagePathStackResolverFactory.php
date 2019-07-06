<?php
namespace HtImgModule\Imagine\Resolver\Factory;

use HtImgModule\Imagine\Resolver\ImagePathStackResolver;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImagePathStackResolverFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $resolvers) {
		$serviceLocator = $resolvers->getServiceLocator();
		$options = $serviceLocator->get('HtImg\ModuleOptions');

		return new ImagePathStackResolver(array(
			'script_paths' => $options->getImgSourcePathStack()
		));
	}
}
