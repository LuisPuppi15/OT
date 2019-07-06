<?php
namespace Portal\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class MenuLateralHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
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

	function __invoke() {
		$sm = $this->getServiceLocator()->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');

		$menSisInfTerritorial = $em->createQueryBuilder()
		                           ->select('it')
		                           ->from('Sistema\Entity\Infoterritorial', 'it')
		                           ->where('it.ninfterestado != :ninfterestado')
		                           ->setParameter('ninfterestado', 0)
		                           ->getQuery()
		                           ->getOneOrNullResult();

		$menPublicaciones = $em->createQueryBuilder()
		                       ->select('p')
		                       ->from('Sistema\Entity\Publicaciones', 'p')
		                       ->where('p.npubestado != :npubestado')
		                       ->setParameter('npubestado', 0)
		                       ->getQuery()
		                       ->getOneOrNullResult();

		$menus = array(
			'menSisInfTerritorial' => $menSisInfTerritorial,
			'menPublicaciones' => $menPublicaciones
		);

		return $menus;
	}
}