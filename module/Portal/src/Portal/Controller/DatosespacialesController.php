<?php
namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DatosespacialesController extends AbstractActionController {
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function inicioAction() {
		$datosespacialess = $this->em->createQueryBuilder()
		                         ->select('de')
		                         ->from('Sistema\Entity\Datosespaciales', 'de')
		                         ->where('de.ndatespestado = 2')
		                         ->orderBy('de.ndatespjerarquia')
		                         ->getQuery()
		                         ->getResult();

		$view = new ViewModel();
		$view->setTemplate('portal/datosespaciales/datosespaciales.phtml');
		$view->setVariable('datosespacialess', $datosespacialess);

		return $view;
	}
}
