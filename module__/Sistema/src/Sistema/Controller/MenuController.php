<?php
namespace Sistema\Controller;

use Sistema\Entity\Html;
use Sistema\Entity\Menu;
use Sistema\Entity\Pdf;
use Sistema\Entity\Url;
use Sistema\Form\HtmlForm;
use Sistema\Form\MenuForm;
use Sistema\Form\PdfForm;
use Sistema\Form\UrlForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class MenuController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 83;
	private $rutaRaiz = 'sistema/menu';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Menu';
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
		$menus = $this->repo->buscar($campos, $paginacion);
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
		$view->setVariable('menus', $menus)
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
		$menus = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/menu/lista.phtml')
		          ->setVariable('menus', $menus)
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
		$menu = new Menu();
		$recargarPadreRuta = $this->url()->fromRoute('sistema/menu', array('action' => 'recargarPadre', 'parametros' => ':parametros'));

		$form = new MenuForm($this->em, $recargarPadreRuta);
		$form->bind($menu);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$requestPost = $request->getPost();
			if ($requestPost['tipocontenido'] == '') {
				$requestPost['tipocontenido'] = null;
			}
			if ($requestPost['padre'] == '') {
				$requestPost['padre'] = null;
			}
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$ultimoOrden = $this->repo->buscarUltimoOrden();
				$cmenjerarquia = $ultimoOrden + 1;

				$menu->setCmenjerarquia($cmenjerarquia);
				$menu->setNmenestado(1);

				$this->em->persist($menu);
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
		$menu = $this->repo->find($id);

		$recargarPadreRuta = $this->url()->fromRoute('sistema/menu', array('action' => 'recargarPadre', 'parametros' => ':parametros'));
		$form = new MenuForm($this->em, $recargarPadreRuta, $menu);
		$form->bind($menu);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$requestPost = $request->getPost();
			if ($requestPost['tipocontenido'] == '') {
				$requestPost['tipocontenido'] = null;
			}
			if ($requestPost['padre'] == '') {
				$requestPost['padre'] = null;
			}
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->em->persist($menu);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'idmenu',
						'id' => $id
					)
				));
			}
		}

		$rutaRaiz = $this->rutaRaiz;

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('ruta', $rutaRaiz)
		     ->setTerminal(true);

		return $view;
	}

	public function eliminarAction() {
		$id = $this->params()->fromRoute('parametros');
		$menu = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$menu->setNmenestado(0);
			$this->em->persist($menu);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array()
			));
		}

		$view = new ViewModel();
		$view->setVariable('menu', $menu)
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

		$menu = $this->repo->find($id);
		$friend = $this->repo->find($friendId);

		$orden = $menu->getCmenjerarquia();
		$friendOrden = $friend->getCmenjerarquia();

		if ($tipo == 'prev') {
			$menusUpd = $this->repo->buscarMayoresParaOrdenar($friendOrden, $orden);

			foreach ($menusUpd as $menuUpd) {
				$menuUpd->setCmenjerarquia($menuUpd->getCmenjerarquia() + 1);
				$this->em->persist($menuUpd);
			}

		} elseif ($tipo == 'next') {
			$menusUpd = $this->repo->buscarMenoresParaOrdenar($friendOrden, $orden);

			foreach ($menusUpd as $menuUpd) {
				$menuUpd->setCmenjerarquia($menuUpd->getCmenjerarquia() - 1);
				$this->em->persist($menuUpd);
			}
		}

		$menu->setCmenjerarquia($friendOrden);
		$this->em->persist($menu);

		$this->em->flush();

		return new JsonModel(array(
			'success' => true,
			'tipo' => 'modal',
			'datos' => array()
		));
	}

	public function contenidoAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$menu = $this->repo->find($id);
		$tipocontenido = $menu->getTipocontenido();

		$view = new ViewModel();

		if ($tipocontenido) {
			switch ($tipocontenido->getNtipconcodigo()) {
				case 1:
					$repoHtml = $this->em->getRepository('Sistema\Entity\Html');
					$html = $repoHtml->buscarActivo($menu->getNmencodigo());

					if (!$html) {
						$html = new Html();
						$html->setDhtmlfechareg(new \DateTime());
						$html->setNhtmlestado(1);
						$html->setMenu($menu);
					}

					$form = new HtmlForm($this->em);
					$form->bind($html);
					$view->setTemplate('sistema/menu/contenido-html.phtml');

					$request = $this->getRequest();
					if ($request->isPost()) {
						$form->setData($request->getPost());

						if ($form->isValid()) {
							$this->em->persist($html);
							$this->em->flush();

							return new JsonModel(array(
								'success' => true,
								'tipo' => 'modal',
								'datos' => array(
									'keyId' => 'idmenu',
									'id' => $id
								)
							));
						}
					}
					break;

				case 2:
					$repoPdf = $this->em->getRepository('Sistema\Entity\Pdf');
					$pdf = $repoPdf->buscarActivo($menu->getNmencodigo());

					if (!$pdf) {
						$pdf = new Pdf();
						$pdf->setDpdffechareg(new \DateTime());
						$pdf->setNpdfestado(1);
						$pdf->setMenu($menu);
					}

					$form = new PdfForm($this->em, $this->publicPath, $pdf);
					$form->bind($pdf);
					$view->setTemplate('sistema/menu/contenido-pdf.phtml');

					$oldPdfString = $pdf->getCpdf();

					$request = $this->getRequest();
					if ($request->isPost()) {
						$post = array_merge_recursive(
							$request->getPost()->toArray(),
							$request->getFiles()->toArray()
						);

						$form->setData($post);
						if ($form->isValid()) {
							// cpdf
							$pdfString = $post['pdfString'];

							if ($pdfString != $oldPdfString) {
								if ($oldPdfString) {
									$this->eliminarArchivo($oldPdfString);
								}

								if ($pdfString != '') {
									$cpdf = $pdf->getCpdf();
									$cpdf = $cpdf['tmp_name'];
									$cpdf = substr(strrchr($cpdf, '/'), 1);
									$pdf->setCpdf($cpdf);
								} else {
									$pdf->setCpdf(null);
								}
							} else {
								$pdf->setCpdf($oldPdfString);
							}

							$this->em->persist($pdf);
							$this->em->flush();

							return new JsonModel(array(
								'success' => true,
								'tipo' => 'modal',
								'datos' => array(
									'keyId' => 'idmenu',
									'id' => $id
								)
							));
						}
					}
					break;

				case 3:
					$repoUrl = $this->em->getRepository('Sistema\Entity\Url');
					$url = $repoUrl->buscarActivo($menu->getNmencodigo());

					if (!$url) {
						$url = new Url();
						$url->setDurlfechareg(new \DateTime());
						$url->setNurlestado(1);
						$url->setMenu($menu);
					}

					$form = new UrlForm($this->em);
					$form->bind($url);
					$view->setTemplate('sistema/menu/contenido-url.phtml');

					$request = $this->getRequest();
					if ($request->isPost()) {
						$form->setData($request->getPost());

						if ($form->isValid()) {
							$this->em->persist($url);
							$this->em->flush();

							return new JsonModel(array(
								'success' => true,
								'tipo' => 'modal',
								'datos' => array(
									'keyId' => 'idmenu',
									'id' => $id
								)
							));
						}
					}
					break;
			}

			$rutaRaiz = $this->rutaRaiz;

			$view->setVariable('id', $id)
			     ->setVariable('form', $form)
			     ->setVariable('ruta', $rutaRaiz)
			     ->setTerminal(true);

		} else {
			$view->setTemplate('sistema/menu/error.phtml');
			$view->setVariable('error', 'El menú seleccionado no tiene un tipo de contenido asociado');
			$view->setTerminal(true);
		}

		return $view;
	}

	public function publicarAction() {
		$id = $this->params()->fromRoute('parametros');
		$menu = $this->repo->find($id);
		$tipocontenido = $menu->getTipocontenido();

		if ($tipocontenido) {
			$existeContenido = $this->repo->buscarSiExisteContenido($menu->getNmencodigo(), $tipocontenido->getNtipconcodigo());

			if ($existeContenido) {
				$form = new Form();
				$submit = new Element\Button('enviar');
				$submit->setLabel('Publicar');
				$submit->setAttributes(array(
					'class' => 'btn btn-modal eliminar'
				));
				$form->add($submit);

				switch ($tipocontenido->getNtipconcodigo()) {
					case 1:
						$repoHtml = $this->em->getRepository('Sistema\Entity\Html');
						$html = $repoHtml->buscarActivo($menu->getNmencodigo());

						if ($html->getNhtmlestado() != 2) {
							$request = $this->getRequest();
							if ($request->isPost()) {
								$html->setDhtmlfechapublico(new \DateTime());
								$html->setNhtmlestado(2);

								$this->em->persist($html);
								$this->em->persist($menu);
								$this->em->flush();

								return new JsonModel(array(
									'success' => true,
									'tipo' => 'modal',
									'datos' => array()
								));
							}
						} else {
							$view = new ViewModel();
							$view->setVariable('error', 'El contenido ya ha sido publicado');
							$view->setTemplate('sistema/menu/error.phtml');
							$view->setTerminal(true);

							return $view;
						}
						break;

					case 2:
						$repoPdf = $this->em->getRepository('Sistema\Entity\Pdf');
						$pdf = $repoPdf->buscarActivo($menu->getNmencodigo());

						if ($pdf->getNpdfestado() != 2) {
							$request = $this->getRequest();
							if ($request->isPost()) {
								$pdf->setDpdffechapublico(new \DateTime());
								$pdf->setNpdfestado(2);

								$this->em->persist($pdf);
								$this->em->persist($menu);
								$this->em->flush();

								return new JsonModel(array(
									'success' => true,
									'tipo' => 'modal',
									'datos' => array()
								));
							}
						} else {
							$view = new ViewModel();
							$view->setVariable('error', 'El contenido ya ha sido publicado');
							$view->setTemplate('sistema/menu/error.phtml');
							$view->setTerminal(true);

							return $view;
						}
						break;

					case 3:
						$repoUrl = $this->em->getRepository('Sistema\Entity\Url');
						$url = $repoUrl->buscarActivo($menu->getNmencodigo());

						if ($url->getNurlestado() != 2) {
							$request = $this->getRequest();
							if ($request->isPost()) {
								$url->setDurlfechapublico(new \DateTime());
								$url->setNurlestado(2);

								$this->em->persist($url);
								$this->em->persist($menu);
								$this->em->flush();

								return new JsonModel(array(
									'success' => true,
									'tipo' => 'modal',
									'datos' => array()
								));
							}
						} else {
							$view = new ViewModel();
							$view->setVariable('error', 'El contenido ya ha sido publicado');
							$view->setTemplate('sistema/menu/error.phtml');
							$view->setTerminal(true);

							return $view;
						}
						break;
				}

				$view = new ViewModel();
				$view->setVariable('menu', $menu)
				     ->setVariable('form', $form)
				     ->setTerminal(true);

				return $view;
			} else {
				$view = new ViewModel();
				$view->setVariable('error', 'El menú seleccionado no tiene un contenido asociado');
				$view->setTemplate('sistema/menu/error.phtml');
				$view->setTerminal(true);

				return $view;
			}
		} else {
			$view = new ViewModel();
			$view->setVariable('error', 'El menú seleccionado no tiene un tipo de contenido asociado');
			$view->setTemplate('sistema/menu/error.phtml');
			$view->setTerminal(true);

			return $view;
		}
	}

	public function recargarPadreAction() {
		$nnivmencodigo = (int) $this->params()->fromRoute('parametros');
		$menus = $this->repo->buscarHabilitadosPorNivel($nnivmencodigo);

		$options = array(
			'<option>(Ninguno)</option>'
		);

		foreach ($menus as $menu) {
			$option = '<option value="' . $menu->getNmencodigo() . '">' . $menu->getCmennombre() . '</option>';
			array_push($options, $option);
		}

		return new JsonModel(array(
			'options' => $options
		));
	}

	private function eliminarArchivo($nombre) {
		$archivo = $this->publicPath . '/upload/pdf/' . $nombre;
		if (file_exists($archivo)) {
			unlink($archivo);
		}
	}
}