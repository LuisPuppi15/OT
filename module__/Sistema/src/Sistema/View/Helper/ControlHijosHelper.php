<?php
namespace Sistema\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class ControlHijosHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
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

	function __invoke($nctrcodigo) {
		$sm = $this->getServiceLocator()->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');

		$controls = $em->createQueryBuilder()
		               ->select('c')
		               ->from('Sistema\Entity\Control', 'c')
		               ->innerJoin('c.padre', 'p')
		               ->where('p.nctrcodigo = :nctrcodigo')
		               ->andWhere('c.nctrestado = 1')
		               ->orderBy('c.cctrjerarquia', 'ASC')
		               ->setParameter('nctrcodigo', $nctrcodigo)
		               ->getQuery()
		               ->getResult();

		return $controls;
	}
}