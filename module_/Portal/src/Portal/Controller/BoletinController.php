<?php
namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BoletinController extends AbstractActionController {
	private $rutaRaiz = 'portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function listaAction() {
		$boletines = $this->em->createQueryBuilder()
		                  ->select('b')
		                  ->from('Sistema\Entity\Boletin', 'b')
		                  ->where('b.nbolestado = 2')
		                  ->orderBy('b.cboljerarquia')
		                  ->getQuery()
		                  ->getResult();

		$view = new ViewModel();
		$view->setTemplate('portal/boletin/boletines.phtml');
		$view->setVariable('boletines', $boletines);

		return $view;
	}
}
