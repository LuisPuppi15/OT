<?php
namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PortadaController extends AbstractActionController {
	private $rutaRaiz = 'portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function inicioAction() {
		$view = new ViewModel();
		$view->setTemplate('portal/portada/portada.phtml');

		$sliders = $this->em->createQueryBuilder()
		                ->select('s')
		                ->from('Sistema\Entity\Slider', 's')
		                ->where('s.nsliestado = 2')
		                ->orderBy('s.cslijerrarquia')
		                ->getQuery()
		                ->getResult();

		$noticias = $this->em->createQueryBuilder()
		                 ->select('n')
		                 ->from('Sistema\Entity\Noticia', 'n')
		                 ->where('n.nnotestado = 2')
		                 ->orderBy('n.dnotfechareg', 'DESC')
		                 ->setFirstResult(0)
		                 ->setMaxResults(5)
		                 ->getQuery()
		                 ->getResult();

		$urlportal = $this->em->createQueryBuilder()
		                  ->select('up')
		                  ->from('Sistema\Entity\Urlportal', 'up')
		                  ->where('up.nurlporestado = :nvidestado')
		                  ->setParameter('nvidestado', 1)
		                  ->getQuery()
		                  ->getOneOrNullResult();

		$videos = $this->em->createQueryBuilder()
		               ->select('v')
		               ->from('Sistema\Entity\Video', 'v')
		               ->where('v.nvidestado = :nvidestado')
		               ->orderBy('v.cvidjerarquia', 'ASC')
		               ->setParameter('nvidestado', 2)
		               ->setFirstResult(0)
		               ->setMaxResults(5)
		               ->getQuery()
		               ->getResult();

		$view->setVariable('sliders', $sliders);
		$view->setVariable('noticias', $noticias);
		$view->setVariable('urlportal', $urlportal);
		$view->setVariable('videos', $videos);

		return $view;
	}
}
