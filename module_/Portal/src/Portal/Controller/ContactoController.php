<?php
namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactoController extends AbstractActionController {
	private $rutaRaiz = 'portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function inicioAction() {
		$contacto = $this->em->createQueryBuilder()
		                 ->select('c')
		                 ->from('Sistema\Entity\Contacto', 'c')
		                 ->where('c.estado = 1')
		                 ->getQuery()
		                 ->getOneOrNullResult();

		$view = new ViewModel();
		$view->setTemplate('portal/contacto/contacto.phtml');
		$view->setVariable('contacto', $contacto);

		return $view;
	}
}
