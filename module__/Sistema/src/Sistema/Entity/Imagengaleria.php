<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imagengaleria
 *
 * @ORM\Table(name="imagengaleria")
 * @ORM\Entity(repositoryClass="Sistema\Repository\ImagengaleriaRepository")
 */
class Imagengaleria {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nimagalcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="imagengaleria_nimagalcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nimagalcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cimagaljerarquia", type="string", length=10, nullable=false)
	 */
	private $cimagaljerarquia;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cimagaltitulo", type="string", length=250, nullable=true)
	 */
	private $cimagaltitulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cimagalimg", type="string", length=250, nullable=false)
	 */
	private $cimagalimg;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dimagalfechareg", type="date", nullable=false)
	 */
	private $dimagalfechareg;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dimagalfechapublico", type="date", nullable=true)
	 */
	private $dimagalfechapublico;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nimagalestado", type="integer", nullable=false)
	 */
	private $nimagalestado;

	/**
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Galeria")
	 * @ORM\JoinColumn(name="ngalcodigo", referencedColumnName="ngalcodigo")
	 */
	protected $galeria;

	/**
	 * Get nimagalcodigo
	 *
	 * @return integer
	 */
	public function getNimagalcodigo() {
		return $this->nimagalcodigo;
	}

	/**
	 * Set cimagaljerarquia
	 *
	 * @param string $cimagaljerarquia
	 * @return Imagengaleria
	 */
	public function setCimagaljerarquia($cimagaljerarquia) {
		$this->cimagaljerarquia = $cimagaljerarquia;

		return $this;
	}

	/**
	 * Get cimagaljerarquia
	 *
	 * @return string
	 */
	public function getCimagaljerarquia() {
		return $this->cimagaljerarquia;
	}

	/**
	 * Set cimagaltitulo
	 *
	 * @param string $cimagaltitulo
	 * @return Imagengaleria
	 */
	public function setCimagaltitulo($cimagaltitulo) {
		$this->cimagaltitulo = $cimagaltitulo;

		return $this;
	}

	/**
	 * Get cimagaltitulo
	 *
	 * @return string
	 */
	public function getCimagaltitulo() {
		return $this->cimagaltitulo;
	}

	/**
	 * Set cimagalimg
	 *
	 * @param string $cimagalimg
	 * @return Imagengaleria
	 */
	public function setCimagalimg($cimagalimg) {
		$this->cimagalimg = $cimagalimg;

		return $this;
	}

	/**
	 * Get cimagalimg
	 *
	 * @return string
	 */
	public function getCimagalimg() {
		return $this->cimagalimg;
	}

	/**
	 * Set dimagalfechareg
	 *
	 * @param \DateTime $dimagalfechareg
	 * @return Imagengaleria
	 */
	public function setDimagalfechareg($dimagalfechareg) {
		$this->dimagalfechareg = $dimagalfechareg;

		return $this;
	}

	/**
	 * Get dimagalfechareg
	 *
	 * @return \DateTime
	 */
	public function getDimagalfechareg() {
		return $this->dimagalfechareg;
	}

	/**
	 * Set dimagalfechapublico
	 *
	 * @param \DateTime $dimagalfechapublico
	 * @return Imagengaleria
	 */
	public function setDimagalfechapublico($dimagalfechapublico) {
		$this->dimagalfechapublico = $dimagalfechapublico;

		return $this;
	}

	/**
	 * Get dimagalfechapublico
	 *
	 * @return \DateTime
	 */
	public function getDimagalfechapublico() {
		return $this->dimagalfechapublico;
	}

	/**
	 * Set nimagalestado
	 *
	 * @param integer $nimagalestado
	 * @return Imagengaleria
	 */
	public function setNimagalestado($nimagalestado) {
		$this->nimagalestado = $nimagalestado;

		return $this;
	}

	/**
	 * Get nimagalestado
	 *
	 * @return integer
	 */
	public function getNimagalestado() {
		return $this->nimagalestado;
	}

	/**
	 * Set galeria
	 *
	 * @param \Sistema\Entity\Galeria $galeria
	 * @return Imagengaleria
	 */
	public function setGaleria(\Sistema\Entity\Galeria $galeria = null) {
		$this->galeria = $galeria;

		return $this;
	}

	/**
	 * Get galeria
	 *
	 * @return \Sistema\Entity\Galeria
	 */
	public function getGaleria() {
		return $this->galeria;
	}
}
