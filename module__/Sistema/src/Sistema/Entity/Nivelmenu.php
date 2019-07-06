<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nivelmenu
 *
 * @ORM\Table(name="nivelmenu")
 * @ORM\Entity(repositoryClass="Sistema\Repository\NivelmenuRepository")
 */
class Nivelmenu {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nnivmencodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="nivelmenu_nnivmencodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nnivmencodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnivmennombre", type="string", length=100, nullable=false)
	 */
	private $cnivmennombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnivmendescripcion", type="string", length=500, nullable=true)
	 */
	private $cnivmendescripcion;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nnivmenestado", type="integer", nullable=false)
	 */
	private $nnivmenestado;

	/**
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Menu", mappedBy="nivelmenu")
	 */
	protected $menus;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->menus = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function __toString() {
		return $this->cnivmennombre;
	}

	/**
	 * Get nnivmencodigo
	 *
	 * @return integer
	 */
	public function getNnivmencodigo() {
		return $this->nnivmencodigo;
	}

	/**
	 * Set cnivmennombre
	 *
	 * @param string $cnivmennombre
	 * @return Nivelmenu
	 */
	public function setCnivmennombre($cnivmennombre) {
		$this->cnivmennombre = $cnivmennombre;

		return $this;
	}

	/**
	 * Get cnivmennombre
	 *
	 * @return string
	 */
	public function getCnivmennombre() {
		return $this->cnivmennombre;
	}

	/**
	 * Set cnivmendescripcion
	 *
	 * @param string $cnivmendescripcion
	 * @return Nivelmenu
	 */
	public function setCnivmendescripcion($cnivmendescripcion) {
		$this->cnivmendescripcion = $cnivmendescripcion;

		return $this;
	}

	/**
	 * Get cnivmendescripcion
	 *
	 * @return string
	 */
	public function getCnivmendescripcion() {
		return $this->cnivmendescripcion;
	}

	/**
	 * Set nnivmenestado
	 *
	 * @param integer $nnivmenestado
	 * @return Nivelmenu
	 */
	public function setNnivmenestado($nnivmenestado) {
		$this->nnivmenestado = $nnivmenestado;

		return $this;
	}

	/**
	 * Get nnivmenestado
	 *
	 * @return integer
	 */
	public function getNnivmenestado() {
		return $this->nnivmenestado;
	}

	/**
	 * Add menus
	 *
	 * @param \Sistema\Entity\Menu $menus
	 * @return Nivelmenu
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
