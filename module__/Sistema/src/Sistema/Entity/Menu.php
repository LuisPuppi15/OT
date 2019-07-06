<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="Sistema\Repository\MenuRepository")
 */
class Menu {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nmencodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="menu_nmencodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nmencodigo;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nmencodpadre", type="integer", nullable=true)
	 */
	private $nmencodpadre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cmenjerarquia", type="string", length=10, nullable=false)
	 */
	private $cmenjerarquia;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cmennombre", type="string", length=250, nullable=false)
	 */
	private $cmennombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cmenvalor", type="string", length=500, nullable=true)
	 */
	private $cmenvalor;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dmenfechapublico", type="date", nullable=true)
	 */
	private $dmenfechapublico;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nmenestado", type="integer", nullable=false)
	 */
	private $nmenestado;

	/**
	 * @var \Sistema\Entity\Menu
	 *
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Menu")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="nmencodpadre", referencedColumnName="nmencodigo")
	 * })
	 */
	private $padre;

	/**
	 * @var \Sistema\Entity\Menu
	 *
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Menu", mappedBy="padre")
	 */
	protected $hijos;

	/**
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Nivelmenu")
	 * @ORM\JoinColumn(name="nnivmencodigo", referencedColumnName="nnivmencodigo")
	 */
	protected $nivelmenu;

	/**
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Tipocontenido")
	 * @ORM\JoinColumn(name="ntipconcodigo", referencedColumnName="ntipconcodigo")
	 */
	protected $tipocontenido;

	/**
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Html", mappedBy="menu")
	 */
	protected $htmls;

	/**
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Pdf", mappedBy="menu")
	 */
	protected $pdfs;

	/**
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Url", mappedBy="menu")
	 */
	protected $urls;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->hijos = new \Doctrine\Common\Collections\ArrayCollection();
		$this->htmls = new \Doctrine\Common\Collections\ArrayCollection();
		$this->pdfs = new \Doctrine\Common\Collections\ArrayCollection();
		$this->urls = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function __toString() {
		$me = $this;
		$nombreCompleto = function ($me, $nombre) use (&$nombreCompleto) {

			if ($me->getPadre()) {
				$nombre .= ' | ' . $me->getPadre()->getCmennombre();
				$nombre = $nombreCompleto($me->getPadre(), $nombre);
			}

			return $nombre;
		};

		return $nombreCompleto($this, $this->getCmennombre());
	}

	/**
	 * Get nmencodigo
	 *
	 * @return integer
	 */
	public function getNmencodigo() {
		return $this->nmencodigo;
	}

	/**
	 * Set nmencodpadre
	 *
	 * @param integer $nmencodpadre
	 * @return Menu
	 */
	public function setNmencodpadre($nmencodpadre) {
		$this->nmencodpadre = $nmencodpadre;

		return $this;
	}

	/**
	 * Get nmencodpadre
	 *
	 * @return integer
	 */
	public function getNmencodpadre() {
		return $this->nmencodpadre;
	}

	/**
	 * Set cmenjerarquia
	 *
	 * @param string $cmenjerarquia
	 * @return Menu
	 */
	public function setCmenjerarquia($cmenjerarquia) {
		$this->cmenjerarquia = $cmenjerarquia;

		return $this;
	}

	/**
	 * Get cmenjerarquia
	 *
	 * @return string
	 */
	public function getCmenjerarquia() {
		return $this->cmenjerarquia;
	}

	/**
	 * Set cmennombre
	 *
	 * @param string $cmennombre
	 * @return Menu
	 */
	public function setCmennombre($cmennombre) {
		$this->cmennombre = $cmennombre;

		return $this;
	}

	/**
	 * Get cmennombre
	 *
	 * @return string
	 */
	public function getCmennombre() {
		return $this->cmennombre;
	}

	/**
	 * Set cmenvalor
	 *
	 * @param string $cmenvalor
	 * @return Menu
	 */
	public function setCmenvalor($cmenvalor) {
		$this->cmenvalor = $cmenvalor;

		return $this;
	}

	/**
	 * Get cmenvalor
	 *
	 * @return string
	 */
	public function getCmenvalor() {
		return $this->cmenvalor;
	}

	/**
	 * Set dmenfechapublico
	 *
	 * @param \DateTime $dmenfechapublico
	 * @return Menu
	 */
	public function setDmenfechapublico($dmenfechapublico) {
		$this->dmenfechapublico = $dmenfechapublico;

		return $this;
	}

	/**
	 * Get dmenfechapublico
	 *
	 * @return \DateTime
	 */
	public function getDmenfechapublico() {
		return $this->dmenfechapublico;
	}

	/**
	 * Set nmenestado
	 *
	 * @param integer $nmenestado
	 * @return Menu
	 */
	public function setNmenestado($nmenestado) {
		$this->nmenestado = $nmenestado;

		return $this;
	}

	/**
	 * Get nmenestado
	 *
	 * @return integer
	 */
	public function getNmenestado() {
		return $this->nmenestado;
	}

	/**
	 * Set padre
	 *
	 * @param \Sistema\Entity\Menu $padre
	 * @return Menu
	 */
	public function setPadre(\Sistema\Entity\Menu $padre = null) {
		$this->padre = $padre;

		return $this;
	}

	/**
	 * Get padre
	 *
	 * @return \Sistema\Entity\Menu
	 */
	public function getPadre() {
		return $this->padre;
	}

	/**
	 * Add hijos
	 *
	 * @param \Sistema\Entity\Menu $hijos
	 * @return Menu
	 */
	public function addHijo(\Sistema\Entity\Menu $hijos) {
		$this->hijos[] = $hijos;

		return $this;
	}

	/**
	 * Remove hijos
	 *
	 * @param \Sistema\Entity\Menu $hijos
	 */
	public function removeHijo(\Sistema\Entity\Menu $hijos) {
		$this->hijos->removeElement($hijos);
	}

	/**
	 * Get hijos
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getHijos() {
		return $this->hijos;
	}

	/**
	 * Set nivelmenu
	 *
	 * @param \Sistema\Entity\Nivelmenu $nivelmenu
	 * @return Menu
	 */
	public function setNivelmenu(\Sistema\Entity\Nivelmenu $nivelmenu = null) {
		$this->nivelmenu = $nivelmenu;

		return $this;
	}

	/**
	 * Get nivelmenu
	 *
	 * @return \Sistema\Entity\Nivelmenu
	 */
	public function getNivelmenu() {
		return $this->nivelmenu;
	}

	/**
	 * Set tipocontenido
	 *
	 * @param \Sistema\Entity\Tipocontenido $tipocontenido
	 * @return Menu
	 */
	public function setTipocontenido(\Sistema\Entity\Tipocontenido $tipocontenido = null) {
		$this->tipocontenido = $tipocontenido;

		return $this;
	}

	/**
	 * Get tipocontenido
	 *
	 * @return \Sistema\Entity\Tipocontenido
	 */
	public function getTipocontenido() {
		return $this->tipocontenido;
	}

	/**
	 * Add htmls
	 *
	 * @param \Sistema\Entity\Html $htmls
	 * @return Menu
	 */
	public function addHtml(\Sistema\Entity\Html $htmls) {
		$this->htmls[] = $htmls;

		return $this;
	}

	/**
	 * Remove htmls
	 *
	 * @param \Sistema\Entity\Html $htmls
	 */
	public function removeHtml(\Sistema\Entity\Html $htmls) {
		$this->htmls->removeElement($htmls);
	}

	/**
	 * Get htmls
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getHtmls() {
		return $this->htmls;
	}

	/**
	 * Add pdfs
	 *
	 * @param \Sistema\Entity\Pdf $pdfs
	 * @return Menu
	 */
	public function addPdf(\Sistema\Entity\Pdf $pdfs) {
		$this->pdfs[] = $pdfs;

		return $this;
	}

	/**
	 * Remove pdfs
	 *
	 * @param \Sistema\Entity\Pdf $pdfs
	 */
	public function removePdf(\Sistema\Entity\Pdf $pdfs) {
		$this->pdfs->removeElement($pdfs);
	}

	/**
	 * Get pdfs
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getPdfs() {
		return $this->pdfs;
	}

	/**
	 * Add urls
	 *
	 * @param \Sistema\Entity\Url $urls
	 * @return Menu
	 */
	public function addUrl(\Sistema\Entity\Url $urls) {
		$this->urls[] = $urls;

		return $this;
	}

	/**
	 * Remove urls
	 *
	 * @param \Sistema\Entity\Url $urls
	 */
	public function removeUrl(\Sistema\Entity\Url $urls) {
		$this->urls->removeElement($urls);
	}

	/**
	 * Get urls
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getUrls() {
		return $this->urls;
	}
}
