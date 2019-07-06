<?php
namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PublicacionController extends AbstractActionController {
	private $rutaRaiz = 'portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function listaAction() {
		$publicaciones = $this->em->createQueryBuilder()
		                      ->select('p')
		                      ->from('Sistema\Entity\Publicacion', 'p')
		                      ->where('p.npubestado = 2')
		                      ->orderBy('p.cpubjerarquia')
		                      ->getQuery()
		                      ->getResult();

		$view = new ViewModel();
		$view->setTemplate('portal/publicacion/publicaciones.phtml');
		$view->setVariable('publicaciones', $publicaciones);

		return $view;
	}
}
