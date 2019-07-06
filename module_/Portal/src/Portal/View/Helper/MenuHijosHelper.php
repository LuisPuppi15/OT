<?php
namespace Portal\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class MenuHijosHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
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

		$menus = $em->createQueryBuilder()
		            ->select('m')
		            ->from('Sistema\Entity\Menu', 'm')
		            ->where('m.padre = :nmencodigo')
		            ->andWhere('m.nmenestado = 1')
		            ->orderBy('m.cmenjerarquia')
		            ->setParameter('nmencodigo', $nmencodigo)
		            ->getQuery()
		            ->getResult();

		return $menus;
	}
}