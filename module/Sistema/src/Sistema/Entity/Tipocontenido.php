<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipocontenido
 *
 * @ORM\Table(name="tipocontenido")
 * @ORM\Entity(repositoryClass="Sistema\Repository\TipocontenidoRepository")
 */
class Tipocontenido {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ntipconcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="tipocontenido_ntipconcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $ntipconcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ctipconnombre", type="string", length=150, nullable=false)
	 */
	private $ctipconnombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ctipcondescripcion", type="string", length=500, nullable=true)
	 */
	private $ctipcondescripcion;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ntipconestado", type="integer", nullable=false)
	 */
	private $ntipconestado;

	/**
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Menu", mappedBy="tipocontenido")
	 */
	protected $menus;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->menus = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function __toString() {
		return $this->ctipconnombre;
	}

	/**
	 * Get ntipconcodigo
	 *
	 * @return integer
	 */
	public function getNtipconcodigo() {
		return $this->ntipconcodigo;
	}

	/**
	 * Set ctipconnombre
	 *
	 * @param string $ctipconnombre
	 * @return Tipocontenido
	 */
	public function setCtipconnombre($ctipconnombre) {
		$this->ctipconnombre = $ctipconnombre;

		return $this;
	}

	/**
	 * Get ctipconnombre
	 *
	 * @return string
	 */
	public function getCtipconnombre() {
		return $this->ctipconnombre;
	}

	/**
	 * Set ctipcondescripcion
	 *
	 * @param string $ctipcondescripcion
	 * @return Tipocontenido
	 */
	public function setCtipcondescripcion($ctipcondescripcion) {
		$this->ctipcondescripcion = $ctipcondescripcion;

		return $this;
	}

	/**
	 * Get ctipcondescripcion
	 *
	 * @return string
	 */
	public function getCtipcondescripcion() {
		return $this->ctipcondescripcion;
	}

	/**
	 * Set ntipconestado
	 *
	 * @param integer $ntipconestado
	 * @return Tipocontenido
	 */
	public function setNtipconestado($ntipconestado) {
		$this->ntipconestado = $ntipconestado;

		return $this;
	}

	/**
	 * Get ntipconestado
	 *
	 * @return integer
	 */
	public function getNtipconestado() {
		return $this->ntipconestado;
	}

	/**
	 * Add menus
	 *
	 * @param \Sistema\Entity\Menu $menus
	 * @return Tipocontenido
	 */
	public function addMenu(\Sistema\Entity\Menu $menus) {
		$this->menus[] = $menus;

		return $this;
	}

	/**
	 * Remove menus
	 *
	 * @param \Sistema\Entity\Menu $menus
	 */
	public function removeMenu(\Sistema\Entity\Menu $menus) {
		$this->menus->removeElement($menus);
	}

	/**
	 * Get menus
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getMenus() {
		return $this->menus;
	}
}
