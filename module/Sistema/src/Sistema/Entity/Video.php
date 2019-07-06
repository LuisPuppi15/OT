<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="Sistema\Repository\VideoRepository")
 */
class Video {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nvidcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="video_nvidcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nvidcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cvidjerarquia", type="integer", nullable=false)
	 */
	private $cvidjerarquia;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dvidfechapublico", type="date", nullable=true)
	 */
	private $dvidfechapublico;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cvidcodigo", type="string", length=50, nullable=false)
	 */
	private $cvidcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cvidtitulo", type="string", length=500, nullable=false)
	 */
	private $cvidtitulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cviddescripcion", type="string", length=1000, nullable=true)
	 */
	private $cviddescripcion;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dvidfechareg", type="datetime", nullable=false)
	 */
	private $dvidfechareg;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nvidestado", type="integer", nullable=false)
	 */
	private $nvidestado;

	/**
	 * Get nvidcodigo
	 *
	 * @return integer
	 */
	public function getNvidcodigo() {
		return $this->nvidcodigo;
	}

	/**
	 * Set cvidjerarquia
	 *
	 * @param integer $cvidjerarquia
	 * @return Video
	 */
	public function setCvidjerarquia($cvidjerarquia) {
		$this->cvidjerarquia = $cvidjerarquia;

		return $this;
	}

	/**
	 * Get cvidjerarquia
	 *
	 * @return integer
	 */
	public function getCvidjerarquia() {
		return $this->cvidjerarquia;
	}

	/**
	 * Set dvidfechapublico
	 *
	 * @param \DateTime $dvidfechapublico
	 * @return Video
	 */
	public function setDvidfechapublico($dvidfechapublico) {
		$this->dvidfechapublico = $dvidfechapublico;

		return $this;
	}

	/**
	 * Get dvidfechapublico
	 *
	 * @return \DateTime
	 */
	public function getDvidfechapublico() {
		return $this->dvidfechapublico;
	}

	/**
	 * Set cvidcodigo
	 *
	 * @param string $cvidcodigo
	 * @return Video
	 */
	public function setCvidcodigo($cvidcodigo) {
		$this->cvidcodigo = $cvidcodigo;

		return $this;
	}

	/**
	 * Get cvidcodigo
	 *
	 * @return string
	 */
	public function getCvidcodigo() {
		return $this->cvidcodigo;
	}

	/**
	 * Set cvidtitulo
	 *
	 * @param string $cvidtitulo
	 * @return Video
	 */
	public function setCvidtitulo($cvidtitulo) {
		$this->cvidtitulo = $cvidtitulo;

		return $this;
	}

	/**
	 * Get cvidtitulo
	 *
	 * @return string
	 */
	public function getCvidtitulo() {
		return $this->cvidtitulo;
	}

	/**
	 * Set cviddescripcion
	 *
	 * @param string $cviddescripcion
	 * @return Video
	 */
	public function setCviddescripcion($cviddescripcion) {
		$this->cviddescripcion = $cviddescripcion;

		return $this;
	}

	/**
	 * Get cviddescripcion
	 *
	 * @return string
	 */
	public function getCviddescripcion() {
		return $this->cviddescripcion;
	}

	/**
	 * Set dvidfechareg
	 *
	 * @param \DateTime $dvidfechareg
	 * @return Video
	 */
	public function setDvidfechareg($dvidfechareg) {
		$this->dvidfechareg = $dvidfechareg;

		return $this;
	}

	/**
	 * Get dvidfechareg
	 *
	 * @return \DateTime
	 */
	public function getDvidfechareg() {
		return $this->dvidfechareg;
	}

	/**
	 * Set nvidestado
	 *
	 * @param integer $nvidestado
	 * @return Video
	 */
	public function setNvidestado($nvidestado) {
		$this->nvidestado = $nvidestado;

		return $this;
	}

	/**
	 * Get nvidestado
	 *
	 * @return integer
	 */
	public function getNvidestado() {
		return $this->nvidestado;
	}
}
