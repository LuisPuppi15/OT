<?php
namespace Sistema\Controller;

use Sistema\Entity\Imagengaleria;
use Sistema\Form\ImagengaleriaForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ImagengaleriaController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 91;
	private $rutaRaiz = 'sistema/imagengaleria';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Imagengaleria';
	private $repo;
	private $publicPath;

	public function __construct() {
		foreach ($this->opcionesItems as $item) {
			if ($item['selected']) {
				$this->items = $item['value'];
			}
		}
	}

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$this->repo = $this->em->getRepository($this->emNamespace);

		$serviceManager = $e->getApplication()->getServiceManager();

		$container = new Container('perusuario');
		$this->usuarioLogueado = $this->em->getRepository('Sistema\Entity\Perusuario')->find($container->id);

		$config = $serviceManager->get('config');
		$this->publicPath = $config['asset_manager']['resolver_configs']['paths'][0];

		return parent::onDispatch($e);
	}

	public function inicioAction() {
		$pagina = 1;
		$items = $this->items;
		$inicio = ($pagina === 0) ? 0 : ($pagina - 1) * $items;

		$paginacion = array(
			'inicio' => $inicio,
			'pagina' => $pagina,
			'items' => $items
		);

		$campos = array();
		$imagengalerias = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$rutaRaiz = $this->rutaRaiz;
		$rutaFiltrar = $this->url()->fromRoute($rutaRaiz, array(
			'action' => 'filtrar',
			'parametros' => ':parametros'
		));

		$repoControl = $this->em->getRepository('Sistema\Entity\Control');
		$nperusucodigo = $this->usuarioLogueado->getNperusucodigo();
		$nctrtipo = 5002;
		$botones = $repoControl->buscarHijosPorUsuario($nperusucodigo, $this->nctrcodigo, $nctrtipo);

		$opcionesItems = $this->opcionesItems;

		$view = new ViewModel();
		$view->setVariable('imagengalerias', $imagengalerias)
		     ->setVariable('botones', $botones)
		     ->setVariable('opcionesItems', $opcionesItems)
		     ->setVariable('paginador', $paginador)
		     ->setVariable('rutaRaiz', $rutaRaiz)
		     ->setVariable('rutaFiltrar', $rutaFiltrar)
		     ->setTerminal(true);

		return $view;
	}

	public function filtrarAction() {
		$strParametros = $this->params()->fromRoute('parametros');
		parse_str($strParametros, $parametros);

		$pagina = (isset($parametros['pagina'])) ? $parametros['pagina'] : 1;
		$pagina = ($pagina != '') ? $pagina : 1;

		$items = (isset($parametros['items'])) ? $parametros['items'] : $this->items;
		$items = ($items != '') ? $items : $this->items;

		$inicio = ($pagina - 1) * $items;

		$paginacion = array(
			'inicio' => $inicio,
			'pagina' => $pagina,
			'items' => $items
		);

		$campos = (isset($parametros['campos'])) ? $parametros['campos'] : array();
		$imagengalerias = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/imagengaleria/lista.phtml')
		          ->setVariable('imagengalerias', $imagengalerias)
		          ->setTerminal(true);

		$rutaRaiz = $this->rutaRaiz;
		$viewPaginador = new ViewModel();
		$viewPaginador->setTemplate('sistema/paginador.phtml')
		              ->setVariable('paginador', $paginador)
		              ->setVariable('ruta', $rutaRaiz)
		              ->setTerminal(true);

		$htmlLista = $this->getServiceLocator()
		                  ->get('viewrenderer')
		                  ->render($viewLista);

		$htmlPaginador = $this->getServiceLocator()
		                      ->get('viewrenderer')
		                      ->render($viewPaginador);

		$jsonModel = new JsonModel();
		$jsonModel->setVariable('htmlLista', $htmlLista)
		          ->setVariable('htmlPaginador', $htmlPaginador);

		return $jsonModel;
	}

	public function agregarAction() {
		$imagengaleria = new Imagengaleria();

		$form = new ImagengaleriaForm($this->em, $this->publicPath);
		$form->bind($imagengaleria);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$requestPost = $request->getPost();
			if ($requestPost['galeria'] == '') {
				$requestPost['galeria'] = null;
			}
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				$cimagalimg = $imagengaleria->getCimagalimg();
				$cimagalimg = $cimagalimg['tmp_name'];

				if ($cimagalimg != '') {
					$cimagalimg = substr(strrchr($cimagalimg, '/'), 1);
					$imagengaleria->setCimagalimg($cimagalimg);
				} else {
					$imagengaleria->setCimagalimg(null);
				}

				$ultimoOrden = $this->repo->buscarUltimoOrden();
				$cnotjerarquia = $ultimoOrden + 1;

				$imagengaleria->setCimagaljerarquia($cnotjerarquia);
				$imagengaleria->setDimagalfechareg(new \DateTime());
				$imagengaleria->setNimagalestado(1);

				$this->em->persist($imagengaleria);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
					)
				));
			}
		}

		$view = new ViewModel();
		$view->setVariable('form', $form);
		$view->setVariable('ruta', $this->rutaRaiz);
		$view->setTerminal(true);

		return $view;
	}

	public function editarAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$imagengaleria = $this->repo->find($id);

		$oldImgString = $imagengaleria->getCimagalimg();

		$form = new ImagengaleriaForm($this->em, $this->publicPath, $imagengaleria);
		$form->bind($imagengaleria);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$requestPost = $request->getPost();
			if ($requestPost['galeria'] == '') {
				$requestPost['galeria'] = null;
			}
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				$imgString = $post['imgString'];

				if ($imgString != $oldImgString) {
					$this->eliminarArchivo($oldImgString);

					if ($imgString != '') {
						$cimagalimg = $imagengaleria->getCimagalimg();
						$cimagalimg = $cimagalimg['tmp_name'];
						$cimagalimg = substr(strrchr($cimagalimg, '/'), 1);
						$imagengaleria->setCimagalimg($cimagalimg);
					} else {
						$imagengaleria->setCimagalimg(null);
					}
				} else {
					$imagengaleria->setCimagalimg($oldImgString);
				}

				$this->em->persist($imagengaleria);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'idimagengaleria',
						'id' => $id
					)
				));
			}
		}

		$rutaRaiz = $this->rutaRaiz;
		$cimagalimg = $imagengaleria->getCimagalimg();

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('cimagalimg', $cimagalimg)
		     ->setVariable('ruta', $rutaRaiz)
		     ->setTerminal(true);

		return $view;
	}

	public function eliminarAction() {
		$id = $this->params()->fromRoute('parametros');
		$imagengaleria = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$imagengaleria->setNimagalestado(0);
			$this->em->persist($imagengaleria);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array()
			));
		}

		$view = new ViewModel();
		$view->setVariable('imagengaleria', $imagengaleria)
		     ->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}

	public function ordenarAction() {
		$strParametros = $this->params()->fromRoute('parametros');
		parse_str($strParametros, $parametros);

		$id = $parametros['id'];
		$friendId = $parametros['friendId'];
		$tipo = $parametros['tipo'];

		$imagengaleria = $this->repo->find($id);
		$friend = $this->repo->find($friendId);

		$orden = $imagengaleria->getCimagaljerarquia();
		$friendOrden = $friend->getCimagaljerarquia();

		if ($tipo == 'prev') {
			$imagengaleriasUpd = $this->repo->buscarMayoresParaOrdenar($friendOrden, $orden);

			foreach ($imagengaleriasUpd as $imagengaleriaUpd) {
				$imagengaleriaUpd->setCimagaljerarquia($imagengaleriaUpd->getCimagaljerarquia() + 1);
				$this->em->persist($imagengaleriaUpd);
			}

		} elseif ($tipo == 'next') {
			$imagengaleriasUpd = $this->repo->buscarMenoresParaOrdenar($friendOrden, $orden);

			foreach ($imagengaleriasUpd as $imagengaleriaUpd) {
				$imagengaleriaUpd->setCimagaljerarquia($imagengaleriaUpd->getCimagaljerarquia() - 1);
				$this->em->persist($imagengaleriaUpd);
			}
		}

		$imagengaleria->setCimagaljerarquia($friendOrden);
		$this->em->persist($imagengaleria);

		$this->em->flush();

		return new JsonModel(array(
			'success' => true,
			'tipo' => 'modal',
			'datos' => array()
		));
	}

	public function publicarAction() {
		$id = $this->params()->fromRoute('parametros');
		$imagengaleria = $this->repo->find($id);

		if ($imagengaleria->getNimagalestado() != 2) {
			$form = new Form();
			$submit = new Element\Button('enviar');
			$submit->setLabel('Publicar');
			$submit->setAttributes(array(
				'class' => 'btn btn-modal eliminar'
			));
			$form->add($submit);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$imagengaleria->setNimagalestado(2);
				$this->em->persist($imagengaleria);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array()
				));
			}

			$view = new ViewModel();
			$view->setVariable('imagengaleria', $imagengaleria)
			     ->setVariable('form', $form)
			     ->setTerminal(true);
		} else {
			$view = new ViewModel();
			$view->setVariable('error', 'El item ya ha sido publicado');
			$view->setTemplate('sistema/imagengaleria/error.phtml');
			$view->setTerminal(true);
		}

		return $view;
	}

	private function eliminarArchivo($nombre) {
		$archivo = $this->publicPath . '/upload/img/' . $nombre;
		if (file_exists($archivo)) {
			unlink($archivo);
		}
	}
}