<?php
namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class GaleriaController extends AbstractActionController {
	private $rutaRaiz = 'portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function listaAction() {
		$codigo = (int) $this->params()->fromRoute('codigo', 0);
		$repoGaleria = $this->em->getRepository('Sistema\Entity\Galeria');
		$galeria = $repoGaleria->find($codigo);

		$view = new ViewModel();

		if ($galeria->getHijos()->count() > 0) {
			$galerias = $this->em->createQueryBuilder()
			                 ->select('g')
			                 ->from('Sistema\Entity\Galeria', 'g')
			                 ->innerJoin('g.padre', 'p')
			                 ->where('p.ngalcodigo = :ngalcodigo')
			                 ->andWhere('g.ngalestado = 2')
			                 ->orderBy('g.cgaljerarquia')
			                 ->setParameter('ngalcodigo', $galeria->getNgalcodigo())
			                 ->getQuery()
			                 ->getResult();

			$view->setVariable('galerias', $galerias);
			$view->setTemplate('portal/galeria/galerias.phtml');

		} else {
			$imagengalerias = $this->em->createQueryBuilder()
			                       ->select('ig')
			                       ->from('Sistema\Entity\Imagengaleria', 'ig')
			                       ->innerJoin('ig.galeria', 'g')
			                       ->where('g.ngalcodigo = :ngalcodigo')
			                       ->andWhere('ig.nimagalestado = 2')
			                       ->orderBy('ig.cimagaljerarquia', 'ASC')
			                       ->setParameter('ngalcodigo', $galeria->getNgalcodigo())
			                       ->getQuery()
			                       ->getResult();
			$view->setVariable('imagengalerias', $imagengalerias);
			$view->setTemplate('portal/galeria/galeria.phtml');
		}

		return $view;
	}
}
