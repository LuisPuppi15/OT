<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Boletin
 *
 * @ORM\Table(name="boletin")
 * @ORM\Entity(repositoryClass="Sistema\Repository\BoletinRepository")
 */
class Boletin {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nbolcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="boletin_nbolcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nbolcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cboljerarquia", type="string", length=10, nullable=false)
	 */
	private $cboljerarquia;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cboltitulo", type="string", length=250, nullable=false)
	 */
	private $cboltitulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cbolrutaimg", type="string", length=250, nullable=false)
	 */
	private $cbolrutaimg;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cbolrutapdf", type="string", length=250, nullable=false)
	 */
	private $cbolrutapdf;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="dbolanio", type="string", length=10, nullable=false)
	 */
	private $dbolanio;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dbolfechareg", type="date", nullable=false)
	 */
	private $dbolfechareg;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dbolfechapublico", type="date", nullable=true)
	 */
	private $dbolfechapublico;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nbolestado", type="integer", nullable=false)
	 */
	private $nbolestado;

	/**
	 * Get nbolcodigo
	 *
	 * @return integer
	 */
	public function getNbolcodigo() {
		return $this->nbolcodigo;
	}

	/**
	 * Set cboljerarquia
	 *
	 * @param string $cboljerarquia
	 * @return Boletin
	 */
	public function setCboljerarquia($cboljerarquia) {
		$this->cboljerarquia = $cboljerarquia;

		return $this;
	}

	/**
	 * Get cboljerarquia
	 *
	 * @return string
	 */
	public function getCboljerarquia() {
		return $this->cboljerarquia;
	}

	/**
	 * Set cboltitulo
	 *
	 * @param string $cboltitulo
	 * @return Boletin
	 */
	public function setCboltitulo($cboltitulo) {
		$this->cboltitulo = $cboltitulo;

		return $this;
	}

	/**
	 * Get cboltitulo
	 *
	 * @return string
	 */
	public function getCboltitulo() {
		return $this->cboltitulo;
	}

	/**
	 * Set cbolrutaimg
	 *
	 * @param string $cbolrutaimg
	 * @return Boletin
	 */
	public function setCbolrutaimg($cbolrutaimg) {
		$this->cbolrutaimg = $cbolrutaimg;

		return $this;
	}

	/**
	 * Get cbolrutaimg
	 *
	 * @return string
	 */
	public function getCbolrutaimg() {
		return $this->cbolrutaimg;
	}

	/**
	 * Set cbolrutapdf
	 *
	 * @param string $cbolrutapdf
	 * @return Boletin
	 */
	public function setCbolrutapdf($cbolrutapdf) {
		$this->cbolrutapdf = $cbolrutapdf;

		return $this;
	}

	/**
	 * Get cbolrutapdf
	 *
	 * @return string
	 */
	public function getCbolrutapdf() {
		return $this->cbolrutapdf;
	}

	/**
	 * Set dbolanio
	 *
	 * @param string $dbolanio
	 * @return Boletin
	 */
	public function setDbolanio($dbolanio) {
		$this->dbolanio = $dbolanio;

		return $this;
	}

	/**
	 * Get dbolanio
	 *
	 * @return string
	 */
	public function getDbolanio() {
		return $this->dbolanio;
	}

	/**
	 * Set dbolfechareg
	 *
	 * @param \DateTime $dbolfechareg
	 * @return Boletin
	 */
	public function setDbolfechareg($dbolfechareg) {
		$this->dbolfechareg = $dbolfechareg;

		return $this;
	}

	/**
	 * Get dbolfechareg
	 *
	 * @return \DateTime
	 */
	public function getDbolfechareg() {
		return $this->dbolfechareg;
	}

	/**
	 * Set dbolfechapublico
	 *
	 * @param \DateTime $dbolfechapublico
	 * @return Boletin
	 */
	public function setDbolfechapublico($dbolfechapublico) {
		$this->dbolfechapublico = $dbolfechapublico;

		return $this;
	}

	/**
	 * Get dbolfechapublico
	 *
	 * @return \DateTime
	 */
	public function getDbolfechapublico() {
		return $this->dbolfechapublico;
	}

	/**
	 * Set nbolestado
	 *
	 * @param integer $nbolestado
	 * @return Boletin
	 */
	public function setNbolestado($nbolestado) {
		$this->nbolestado = $nbolestado;

		return $this;
	}

	/**
	 * Get nbolestado
	 *
	 * @return integer
	 */
	public function getNbolestado() {
		return $this->nbolestado;
	}
}
