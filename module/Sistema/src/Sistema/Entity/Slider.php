<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slider
 *
 * @ORM\Table(name="slider")
 * @ORM\Entity(repositoryClass="Sistema\Repository\SliderRepository")
 */
class Slider {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nslicodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="slider_nslicodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nslicodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cslijerrarquia", type="integer", nullable=true)
	 */
	private $cslijerrarquia;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cslititulo", type="string", length=250, nullable=false)
	 */
	private $cslititulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cslidescripcion", type="string", length=250, nullable=true)
	 */
	private $cslidescripcion;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cslirutimgfull", type="string", length=500, nullable=false)
	 */
	private $cslirutimgfull;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cslirutimgmin", type="string", length=500, nullable=true)
	 */
	private $cslirutimgmin;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dslifechareg", type="date", nullable=false)
	 */
	private $dslifechareg;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dslifechapublico", type="date", nullable=true)
	 */
	private $dslifechapublico;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nsliestado", type="integer", nullable=false)
	 */
	private $nsliestado;

	/**
	 * Get nslicodigo
	 *
	 * @return integer
	 */
	public function getNslicodigo() {
		return $this->nslicodigo;
	}

	/**
	 * Set cslijerrarquia
	 *
	 * @param integer $cslijerrarquia
	 * @return Slider
	 */
	public function setCslijerrarquia($cslijerrarquia) {
		$this->cslijerrarquia = $cslijerrarquia;

		return $this;
	}

	/**
	 * Get cslijerrarquia
	 *
	 * @return integer
	 */
	public function getCslijerrarquia() {
		return $this->cslijerrarquia;
	}

	/**
	 * Set cslititulo
	 *
	 * @param string $cslititulo
	 * @return Slider
	 */
	public function setCslititulo($cslititulo) {
		$this->cslititulo = $cslititulo;

		return $this;
	}

	/**
	 * Get cslititulo
	 *
	 * @return string
	 */
	public function getCslititulo() {
		return $this->cslititulo;
	}

	/**
	 * Set cslidescripcion
	 *
	 * @param string $cslidescripcion
	 * @return Slider
	 */
	public function setCslidescripcion($cslidescripcion) {
		$this->cslidescripcion = $cslidescripcion;

		return $this;
	}

	/**
	 * Get cslidescripcion
	 *
	 * @return string
	 */
	public function getCslidescripcion() {
		return $this->cslidescripcion;
	}

	/**
	 * Set cslirutimgfull
	 *
	 * @param string $cslirutimgfull
	 * @return Slider
	 */
	public function setCslirutimgfull($cslirutimgfull) {
		$this->cslirutimgfull = $cslirutimgfull;

		return $this;
	}

	/**
	 * Get cslirutimgfull
	 *
	 * @return string
	 */
	public function getCslirutimgfull() {
		return $this->cslirutimgfull;
	}

	/**
	 * Set cslirutimgmin
	 *
	 * @param string $cslirutimgmin
	 * @return Slider
	 */
	public function setCslirutimgmin($cslirutimgmin) {
		$this->cslirutimgmin = $cslirutimgmin;

		return $this;
	}

	/**
	 * Get cslirutimgmin
	 *
	 * @return string
	 */
	public function getCslirutimgmin() {
		return $this->cslirutimgmin;
	}

	/**
	 * Set dslifechareg
	 *
	 * @param \DateTime $dslifechareg
	 * @return Slider
	 */
	public function setDslifechareg($dslifechareg) {
		$this->dslifechareg = $dslifechareg;

		return $this;
	}

	/**
	 * Get dslifechareg
	 *
	 * @return \DateTime
	 */
	public function getDslifechareg() {
		return $this->dslifechareg;
	}

	/**
	 * Set dslifechapublico
	 *
	 * @param \DateTime $dslifechapublico
	 * @return Slider
	 */
	public function setDslifechapublico($dslifechapublico) {
		$this->dslifechapublico = $dslifechapublico;

		return $this;
	}

	/**
	 * Get dslifechapublico
	 *
	 * @return \DateTime
	 */
	public function getDslifechapublico() {
		return $this->dslifechapublico;
	}

	/**
	 * Set nsliestado
	 *
	 * @param integer $nsliestado
	 * @return Slider
	 */
	public function setNsliestado($nsliestado) {
		$this->nsliestado = $nsliestado;

		return $this;
	}

	/**
	 * Get nsliestado
	 *
	 * @return integer
	 */
	public function getNsliestado() {
		return $this->nsliestado;
	}
}
