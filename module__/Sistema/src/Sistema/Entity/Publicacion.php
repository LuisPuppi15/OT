<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publicacion
 *
 * @ORM\Table(name="publicacion")
 * @ORM\Entity(repositoryClass="Sistema\Repository\PublicacionRepository")
 */
class Publicacion {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="npubcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="publicacion_npubcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $npubcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpubjerarquia", type="string", length=10, nullable=false)
	 */
	private $cpubjerarquia;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpubtitulo", type="string", length=200, nullable=false)
	 */
	private $cpubtitulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpubanio", type="string", length=20, nullable=false)
	 */
	private $cpubanio;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpubimagen", type="string", length=200, nullable=false)
	 */
	private $cpubimagen;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpubpdf", type="string", length=200, nullable=false)
	 */
	private $cpubpdf;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="npubestado", type="integer", nullable=false)
	 */
	private $npubestado;

	/**
	 * Get npubcodigo
	 *
	 * @return integer
	 */
	public function getNpubcodigo() {
		return $this->npubcodigo;
	}

	/**
	 * Set cpubjerarquia
	 *
	 * @param string $cpubjerarquia
	 * @return Publicacion
	 */
	public function setCpubjerarquia($cpubjerarquia) {
		$this->cpubjerarquia = $cpubjerarquia;

		return $this;
	}

	/**
	 * Get cpubjerarquia
	 *
	 * @return string
	 */
	public function getCpubjerarquia() {
		return $this->cpubjerarquia;
	}

	/**
	 * Set cpubtitulo
	 *
	 * @param string $cpubtitulo
	 * @return Publicacion
	 */
	public function setCpubtitulo($cpubtitulo) {
		$this->cpubtitulo = $cpubtitulo;

		return $this;
	}

	/**
	 * Get cpubtitulo
	 *
	 * @return string
	 */
	public function getCpubtitulo() {
		return $this->cpubtitulo;
	}

	/**
	 * Set cpubanio
	 *
	 * @param string $cpubanio
	 * @return Publicacion
	 */
	public function setCpubanio($cpubanio) {
		$this->cpubanio = $cpubanio;

		return $this;
	}

	/**
	 * Get cpubanio
	 *
	 * @return string
	 */
	public function getCpubanio() {
		return $this->cpubanio;
	}

	/**
	 * Set cpubimagen
	 *
	 * @param string $cpubimagen
	 * @return Publicacion
	 */
	public function setCpubimagen($cpubimagen) {
		$this->cpubimagen = $cpubimagen;

		return $this;
	}

	/**
	 * Get cpubimagen
	 *
	 * @return string
	 */
	public function getCpubimagen() {
		return $this->cpubimagen;
	}

	/**
	 * Set cpubpdf
	 *
	 * @param string $cpubpdf
	 * @return Publicacion
	 */
	public function setCpubpdf($cpubpdf) {
		$this->cpubpdf = $cpubpdf;

		return $this;
	}

	/**
	 * Get cpubpdf
	 *
	 * @return string
	 */
	public function getCpubpdf() {
		return $this->cpubpdf;
	}

	/**
	 * Set npubestado
	 *
	 * @param integer $npubestado
	 * @return Publicacion
	 */
	public function setNpubestado($npubestado) {
		$this->npubestado = $npubestado;

		return $this;
	}

	/**
	 * Get npubestado
	 *
	 * @return integer
	 */
	public function getNpubestado() {
		return $this->npubestado;
	}
}
