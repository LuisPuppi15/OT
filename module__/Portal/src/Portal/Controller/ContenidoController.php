<?php
namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContenidoController extends AbstractActionController {
	private $rutaRaiz = 'portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function detalleAction() {
		$codigo = (int) $this->params()->fromRoute('codigo', 0);
		$view = new ViewModel();
		$view->setTemplate('portal/contenido/contenido.phtml');

		if ($codigo) {
			$repoHtml = $this->em->getRepository('Sistema\Entity\Html');
			$html = $repoHtml->find($codigo);

			if ($html) {
				$contenido = $html->getChtmlcontenido();
				$view->setTemplate('portal/contenido/contenido-detalle.phtml');
				$view->setVariable('contenido', $contenido);
			}
		}

		return $view;
	}
}
