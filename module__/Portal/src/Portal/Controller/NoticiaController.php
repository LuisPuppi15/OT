<?php
namespace Portal\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class NoticiaController extends AbstractActionController {
	private $rutaRaiz = 'portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		return parent::onDispatch($e);
	}

	public function detalleAction() {
		$codigo = (int) $this->params()->fromRoute('codigo', 0);
		$view = new ViewModel();
		$view->setTemplate('portal/noticia/noticia.phtml');

		if ($codigo) {
			$repoNoticia = $this->em->getRepository('Sistema\Entity\Noticia');
			$noticia = $repoNoticia->find($codigo);

			if ($noticia) {
				$detalle = $noticia->getCnotcontenido();
				$view->setTemplate('portal/noticia/noticia-detalle.phtml');
				$view->setVariable('detalle', $detalle);
			}
		}

		return $view;
	}

	public function listaAction() {
		$pagina = (int) $this->params()->fromRoute('pagina', 1);
		$cantidad = 10;
		$inicio = $cantidad * ($pagina - 1);

		$query = $this->em->createQueryBuilder()
		              ->select('n')
		              ->from('Sistema\Entity\Noticia', 'n')
		              ->where('n.nnotestado = 2')
		              ->orderBy('n.dnotfechareg', 'DESC')
		              ->setFirstResult($inicio)
		              ->setMaxResults($cantidad)
		              ->getQuery();

		$noticias = $query->getResult();

		$adapter = new DoctrineAdapter(new ORMPaginator($query));
		$paginador = new Paginator($adapter);
		$paginador->setDefaultItemCountPerPage($cantidad);
		$paginador->setCurrentPageNumber($pagina);

		$view = new ViewModel();
		$view->setTemplate('portal/noticia/noticias.phtml');
		$view->setVariable('noticias', $noticias);
		$view->setVariable('paginador', $paginador);
		$view->setVariable('ruta', 'portal/noticias');

		return $view;
	}
}
