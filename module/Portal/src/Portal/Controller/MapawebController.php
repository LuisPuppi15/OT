<?php
namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MapawebController extends AbstractActionController {
	private $rutaRaiz = 'portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function inicioAction() {
		$view = new ViewModel();
		$view->setTemplate('portal/mapaweb/mapaweb.phtml');

		return $view;
	}
}
