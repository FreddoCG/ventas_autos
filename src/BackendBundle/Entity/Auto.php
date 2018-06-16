<?php
/**
 * Created by PhpStorm.
 * User: freddocg
 */
namespace BackendBundle\Entity;

/**
 * Auto
 */
class Auto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $marca;

    /**
     * @var string
     */
    private $modelo;

    /**
     * @var string
     */
    private $anio;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $precio;

    /**
     * @var \DateTime
     */
    private $fechaRegistro;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set marca
     *
     * @param string $marca
     *
     * @return Auto
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set modelo
     *
     * @param string $modelo
     *
     * @return Auto
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get modelo
     *
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Set anio
     *
     * @param string $anio
     *
     * @return Auto
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return string
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Auto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Auto
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return Auto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return Auto
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }
}

