<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Noticia
 *
 * @ORM\Table(name="noticia")
 * @ORM\Entity(repositoryClass="Sistema\Repository\NoticiaRepository")
 */
class Noticia {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nnotcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="noticia_nnotcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nnotcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnotjerarquia", type="string", length=10, nullable=false)
	 */
	private $cnotjerarquia;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnottitulo", type="string", length=250, nullable=false)
	 */
	private $cnottitulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnotpietitulo", type="string", length=500, nullable=true)
	 */
	private $cnotpietitulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnotrutaimgfull", type="string", length=500, nullable=false)
	 */
	private $cnotrutaimgfull;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnotrutaimgmin", type="string", length=500, nullable=true)
	 */
	private $cnotrutaimgmin;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnotleyenda", type="string", length=500, nullable=true)
	 */
	private $cnotleyenda;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cnotcontenido", type="text", nullable=false)
	 */
	private $cnotcontenido;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dnotfecha", type="date", nullable=false)
	 */
	private $dnotfecha;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dnotfechareg", type="date", nullable=false)
	 */
	private $dnotfechareg;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dnotfechapublico", type="date", nullable=true)
	 */
	private $dnotfechapublico;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nnotestado", type="integer", nullable=false)
	 */
	private $nnotestado;

	/**
	 * Get nnotcodigo
	 *
	 * @return integer
	 */
	public function getNnotcodigo() {
		return $this->nnotcodigo;
	}

	/**
	 * Set cnotjerarquia
	 *
	 * @param string $cnotjerarquia
	 * @return Noticia
	 */
	public function setCnotjerarquia($cnotjerarquia) {
		$this->cnotjerarquia = $cnotjerarquia;

		return $this;
	}

	/**
	 * Get cnotjerarquia
	 *
	 * @return string
	 */
	public function getCnotjerarquia() {
		return $this->cnotjerarquia;
	}

	/**
	 * Set cnottitulo
	 *
	 * @param string $cnottitulo
	 * @return Noticia
	 */
	public function setCnottitulo($cnottitulo) {
		$this->cnottitulo = $cnottitulo;

		return $this;
	}

	/**
	 * Get cnottitulo
	 *
	 * @return string
	 */
	public function getCnottitulo() {
		return $this->cnottitulo;
	}

	/**
	 * Set cnotpietitulo
	 *
	 * @param string $cnotpietitulo
	 * @return Noticia
	 */
	public function setCnotpietitulo($cnotpietitulo) {
		$this->cnotpietitulo = $cnotpietitulo;

		return $this;
	}

	/**
	 * Get cnotpietitulo
	 *
	 * @return string
	 */
	public function getCnotpietitulo() {
		return $this->cnotpietitulo;
	}

	/**
	 * Set cnotrutaimgfull
	 *
	 * @param string $cnotrutaimgfull
	 * @return Noticia
	 */
	public function setCnotrutaimgfull($cnotrutaimgfull) {
		$this->cnotrutaimgfull = $cnotrutaimgfull;

		return $this;
	}

	/**
	 * Get cnotrutaimgfull
	 *
	 * @return string
	 */
	public function getCnotrutaimgfull() {
		return $this->cnotrutaimgfull;
	}

	/**
	 * Set cnotrutaimgmin
	 *
	 * @param string $cnotrutaimgmin
	 * @return Noticia
	 */
	public function setCnotrutaimgmin($cnotrutaimgmin) {
		$this->cnotrutaimgmin = $cnotrutaimgmin;

		return $this;
	}

	/**
	 * Get cnotrutaimgmin
	 *
	 * @return string
	 */
	public function getCnotrutaimgmin() {
		return $this->cnotrutaimgmin;
	}

	/**
	 * Set cnotleyenda
	 *
	 * @param string $cnotleyenda
	 * @return Noticia
	 */
	public function setCnotleyenda($cnotleyenda) {
		$this->cnotleyenda = $cnotleyenda;

		return $this;
	}

	/**
	 * Get cnotleyenda
	 *
	 * @return string
	 */
	public function getCnotleyenda() {
		return $this->cnotleyenda;
	}

	/**
	 * Set cnotcontenido
	 *
	 * @param string $cnotcontenido
	 * @return Noticia
	 */
	public function setCnotcontenido($cnotcontenido) {
		$this->cnotcontenido = $cnotcontenido;

		return $this;
	}

	/**
	 * Get cnotcontenido
	 *
	 * @return string
	 */
	public function getCnotcontenido() {
		return $this->cnotcontenido;
	}

	/**
	 * Set dnotfecha
	 *
	 * @param \DateTime $dnotfecha
	 * @return Noticia
	 */
	public function setDnotfecha($dnotfecha) {
		$this->dnotfecha = $dnotfecha;

		return $this;
	}

	/**
	 * Get dnotfecha
	 *
	 * @return \DateTime
	 */
	public function getDnotfecha() {
		return $this->dnotfecha;
	}

	/**
	 * Set dnotfechareg
	 *
	 * @param \DateTime $dnotfechareg
	 * @return Noticia
	 */
	public function setDnotfechareg($dnotfechareg) {
		$this->dnotfechareg = $dnotfechareg;

		return $this;
	}

	/**
	 * Get dnotfechareg
	 *
	 * @return \DateTime
	 */
	public function getDnotfechareg() {
		return $this->dnotfechareg;
	}

	/**
	 * Set dnotfechapublico
	 *
	 * @param \DateTime $dnotfechapublico
	 * @return Noticia
	 */
	public function setDnotfechapublico($dnotfechapublico) {
		$this->dnotfechapublico = $dnotfechapublico;

		return $this;
	}

	/**
	 * Get dnotfechapublico
	 *
	 * @return \DateTime
	 */
	public function getDnotfechapublico() {
		return $this->dnotfechapublico;
	}

	/**
	 * Set nnotestado
	 *
	 * @param integer $nnotestado
	 * @return Noticia
	 */
	public function setNnotestado($nnotestado) {
		$this->nnotestado = $nnotestado;

		return $this;
	}

	/**
	 * Get nnotestado
	 *
	 * @return integer
	 */
	public function getNnotestado() {
		return $this->nnotestado;
	}
}
