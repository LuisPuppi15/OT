<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Perusuacceso
 *
 * @ORM\Table(name="perusuacceso", indexes={@ORM\Index(name="IDX_9B11199BB5E1D18", columns={"nperusucodigo"}), @ORM\Index(name="IDX_9B111998B563386", columns={"nctrcodigo"})})
 * @ORM\Entity(repositoryClass="Sistema\Repository\PerusuaccesoRepository")
 */
class Perusuacceso {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nperusuaccestado", type="integer", nullable=false)
	 */
	private $nperusuaccestado;

	/**
	 * @var \Sistema\Entity\Perusuario
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Perusuario", inversedBy="perusuaccesos")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="nperusucodigo", referencedColumnName="nperusucodigo")
	 * })
	 */
	private $perusuario;

	/**
	 * @var \Sistema\Entity\Control
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Control", inversedBy="perusuaccesos")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="nctrcodigo", referencedColumnName="nctrcodigo")
	 * })
	 */
	private $control;

	/**
	 * Set nperusuaccestado
	 *
	 * @param integer $nperusuaccestado
	 * @return Perusuacceso
	 */
	public function setNperusuaccestado($nperusuaccestado) {
		$this->nperusuaccestado = $nperusuaccestado;

		return $this;
	}

	/**
	 * Get nperusuaccestado
	 *
	 * @return integer
	 */
	public function getNperusuaccestado() {
		return $this->nperusuaccestado;
	}

	/**
	 * Set perusuario
	 *
	 * @param \Sistema\Entity\Perusuario $perusuario
	 * @return Perusuacceso
	 */
	public function setPerusuario(\Sistema\Entity\Perusuario $perusuario) {
		$this->perusuario = $perusuario;

		return $this;
	}

	/**
	 * Get perusuario
	 *
	 * @return \Sistema\Entity\Perusuario
	 */
	public function getPerusuario() {
		return $this->perusuario;
	}

	/**
	 * Set control
	 *
	 * @param \Sistema\Entity\Control $control
	 * @return Perusuacceso
	 */
	public function setControl(\Sistema\Entity\Control $control) {
		$this->control = $control;

		return $this;
	}

	/**
	 * Get control
	 *
	 * @return \Sistema\Entity\Control
	 */
	public function getControl() {
		return $this->control;
	}
}
