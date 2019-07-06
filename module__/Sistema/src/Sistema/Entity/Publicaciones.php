<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publicaciones
 *
 * @ORM\Table(name="publicaciones")
 * @ORM\Entity(repositoryClass="Sistema\Repository\PublicacionesRepository")
 */
class Publicaciones {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="npubcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="publicaciones_npubcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $npubcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpubnombre", type="string", length=100, nullable=true)
	 */
	private $cpubnombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpuburl", type="string", length=500, nullable=false)
	 */
	private $cpuburl;

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
	 * Set cpubnombre
	 *
	 * @param string $cpubnombre
	 * @return Publicaciones
	 */
	public function setCpubnombre($cpubnombre) {
		$this->cpubnombre = $cpubnombre;

		return $this;
	}

	/**
	 * Get cpubnombre
	 *
	 * @return string
	 */
	public function getCpubnombre() {
		return $this->cpubnombre;
	}

	/**
	 * Set cpuburl
	 *
	 * @param string $cpuburl
	 * @return Publicaciones
	 */
	public function setCpuburl($cpuburl) {
		$this->cpuburl = $cpuburl;

		return $this;
	}

	/**
	 * Get cpuburl
	 *
	 * @return string
	 */
	public function getCpuburl() {
		return $this->cpuburl;
	}

	/**
	 * Set npubestado
	 *
	 * @param integer $npubestado
	 * @return Publicaciones
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
