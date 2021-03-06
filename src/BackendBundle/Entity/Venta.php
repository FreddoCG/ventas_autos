<?php
/**
 * Created by PhpStorm.
 * User: freddocg
 */
namespace BackendBundle\Entity;

/**
 * Venta
 */
class Venta
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fechaVenta = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     */
    private $observacion;

    /**
     * @var \BackendBundle\Entity\Auto
     */
    private $auto;

    /**
     * @var \BackendBundle\Entity\Cliente
     */
    private $cliente;

    /**
     * @var \BackendBundle\Entity\Empleado
     */
    private $empleado;


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
     * Set fechaVenta
     *
     * @param \DateTime $fechaVenta
     *
     * @return Venta
     */
    public function setFechaVenta($fechaVenta)
    {
        $this->fechaVenta = $fechaVenta;

        return $this;
    }

    /**
     * Get fechaVenta
     *
     * @return \DateTime
     */
    public function getFechaVenta()
    {
        return $this->fechaVenta;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return Venta
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set auto
     *
     * @param \BackendBundle\Entity\Auto $auto
     *
     * @return Venta
     */
    public function setAuto(\BackendBundle\Entity\Auto $auto = null)
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * Get auto
     *
     * @return \BackendBundle\Entity\Auto
     */
    public function getAuto()
    {
        return $this->auto;
    }

    /**
     * Set cliente
     *
     * @param \BackendBundle\Entity\Cliente $cliente
     *
     * @return Venta
     */
    public function setCliente(\BackendBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \BackendBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set empleado
     *
     * @param \BackendBundle\Entity\Empleado $empleado
     *
     * @return Venta
     */
    public function setEmpleado(\BackendBundle\Entity\Empleado $empleado = null)
    {
        $this->empleado = $empleado;

        return $this;
    }

    /**
     * Get empleado
     *
     * @return \BackendBundle\Entity\Empleado
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }
}
