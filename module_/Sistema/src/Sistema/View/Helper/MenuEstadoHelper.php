<?php
namespace Sistema\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class MenuEstadoHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
	/**
	 * Set the service locator.
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return CustomHelper
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	/**
	 * Get the service locator.
	 *
	 * @return \Zend\ServiceManager\ServiceLocatorInterface
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	function __invoke($nmencodigo) {
		$sm = $this->getServiceLocator()->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');

		$repoMenu = $em->getRepository('Sistema\Entity\Menu');
		$estado = $repoMenu->enviarEstado($nmencodigo);

		return $estado;
	}
}