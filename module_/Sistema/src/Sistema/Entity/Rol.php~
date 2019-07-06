<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rol
 *
 * @ORM\Table(name="rol")
 * @ORM\Entity(repositoryClass="Sistema\Repository\RolRepository")
 */
class Rol {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nrolcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="rol_nrolcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nrolcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="crolnombre", type="string", length=100, nullable=false)
	 */
	private $crolnombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="croldescripcion", type="string", length=200, nullable=true)
	 */
	private $croldescripcion;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nrolestado", type="integer", nullable=false)
	 */
	private $nrolestado;

	/**
	 * @var \Sistema\Entity\Rolcontrol
	 *
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Rolcontrol", mappedBy="rol")
	 */
	protected $rolcontrols;

	/**
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Perusuario", mappedBy="rol")
	 */
	protected $perusuarios;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->rolcontrols = new \Doctrine\Common\Collections\ArrayCollection();
		$this->perusuarios = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function __toString() {
		return $this->crolnombre;
	}

	/**
	 * Get nrolcodigo
	 *
	 * @return integer
	 */
	public function getNrolcodigo() {
		return $this->nrolcodigo;
	}

	/**
	 * Set crolnombre
	 *
	 * @param string $crolnombre
	 * @return Rol
	 */
	public function setCrolnombre($crolnombre) {
		$this->crolnombre = $crolnombre;

		return $this;
	}

	/**
	 * Get crolnombre
	 *
	 * @return string
	 */
	public function getCrolnombre() {
		return $this->crolnombre;
	}

	/**
	 * Set croldescripcion
	 *
	 * @param string $croldescripcion
	 * @return Rol
	 */
	public function setCroldescripcion($croldescripcion) {
		$this->croldescripcion = $croldescripcion;

		return $this;
	}

	/**
	 * Get croldescripcion
	 *
	 * @return string
	 */
	public function getCroldescripcion() {
		return $this->croldescripcion;
	}

	/**
	 * Set nrolestado
	 *
	 * @param integer $nrolestado
	 * @return Rol
	 */
	public function setNrolestado($nrolestado) {
		$this->nrolestado = $nrolestado;

		return $this;
	}

	/**
	 * Get nrolestado
	 *
	 * @return integer
	 */
	public function getNrolestado() {
		return $this->nrolestado;
	}

	/**
	 * Add rolcontrols
	 *
	 * @param \Sistema\Entity\Rolcontrol $rolcontrols
	 * @return Rol
	 */
	public function addRolcontrol(\Sistema\Entity\Rolcontrol $rolcontrols) {
		$this->rolcontrols[] = $rolcontrols;

		return $this;
	}

	/**
	 * Remove rolcontrols
	 *
	 * @param \Sistema\Entity\Rolcontrol $rolcontrols
	 */
	public function removeRolcontrol(\Sistema\Entity\Rolcontrol $rolcontrols) {
		$this->rolcontrols->removeElement($rolcontrols);
	}

	/**
	 * Get rolcontrols
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getRolcontrols() {
		return $this->rolcontrols;
	}

	/**
	 * Add perusuarios
	 *
	 * @param \Sistema\Entity\Perusuario $perusuarios
	 * @return Rol
	 */
	public function addPerusuario(\Sistema\Entity\Perusuario $perusuarios) {
		$this->perusuarios[] = $perusuarios;

		return $this;
	}

	/**
	 * Remove perusuarios
	 *
	 * @param \Sistema\Entity\Perusuario $perusuarios
	 */
	public function removePerusuario(\Sistema\Entity\Perusuario $perusuarios) {
		$this->perusuarios->removeElement($perusuarios);
	}

	/**
	 * Get perusuarios
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getPerusuarios() {
		return $this->perusuarios;
	}
}
