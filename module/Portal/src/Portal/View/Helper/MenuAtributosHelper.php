<?php
namespace Portal\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class MenuAtributosHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
	/**
	 * Set the service locator.
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return CustomHelper
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	/**
	 * Get the service locator.
	 *
	 * @return \Zend\ServiceManager\ServiceLocatorInterface
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	function __invoke($menu) {
		$sm = $this->getServiceLocator()->getServiceLocator();
		$em = $sm->get('Doctrine\ORM\EntityManager');

		$atributos = array(
			'target' => '_self',
			'href' => '#'
		);

		$tipocontenido = $menu->getTipocontenido();

		if ($tipocontenido) {
			$ntipconcodigo = $tipocontenido->getNtipconcodigo();

			switch ($ntipconcodigo) {
				case 1:
					$repoHtml = $em->getRepository('Sistema\Entity\Html');
					$html = $repoHtml->buscarPublicado($menu->getNmencodigo());

					if ($html) {
						$atributos['href'] = $this->view->url('portal/contenido', array('codigo' => $html->getNhtmlcodigo()));
					} else {
						// $atributos['href'] = '#';
					}
					break;

				case 2:
					$repoPdf = $em->getRepository('Sistema\Entity\Pdf');
					$pdf = $repoPdf->buscarPublicado($menu->getNmencodigo());

					if ($pdf) {
						$atributos['target'] = '_blank';
						$atributos['href'] = 'upload/pdf/' . $pdf->getCpdf();
					} else {
						// $atributos['href'] = '#';
					}
					break;

				case 3:
					$repoUrl = $em->getRepository('Sistema\Entity\Url');
					$url = $repoUrl->buscarPublicado($menu->getNmencodigo());

					if ($url) {
						$atributos['href'] = $this->view->url($url->getCurl());
					} else {
						// $atributos['href'] = '#';
					}
					break;
			}
		}

		return $atributos;
	}
}