<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\StockRepository")
 */
class Stock
{
    /**
     * @return mixed
     */
    public function getIdProduct()
    {
        return $this->id_product;
    }

    /**
     * @param mixed $id_product
     */
    public function setIdProduct($id_product)
    {
        $this->id_product = $id_product;
    }

    /**
     * @return mixed
     */
    public function getIdEntrepot()
    {
        return $this->id_entrepot;
    }

    /**
     * @param mixed $id_entrepot
     */
    public function setIdEntrepot($id_entrepot)
    {
        $this->id_entrepot = $id_entrepot;
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="unity", type="string", length=255)
     */
    private $unity;

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }


    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product", referencedColumnName="id_product")
     * */
    private $product;



    /**
     * @ORM\ManyToOne(targetEntity="Entrepot")
     * @ORM\JoinColumn(name="entrepot", referencedColumnName="id")
     * */
    private $entrepot;

    /**
     * @return mixed
     */
    public function getEntrepot()
    {
        return $this->entrepot;
    }

    /**
     * @param mixed $entrepot
     */
    public function setEntrepot($entrepot)
    {
        $this->entrepot = $entrepot;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     *
     * @return Stock
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set unity
     *
     * @param string $unity
     *
     * @return Stock
     */
    public function setUnity($unity)
    {
        $this->unity = $unity;

        return $this;
    }

    /**
     * Get unity
     *
     * @return string
     */
    public function getUnity()
    {
        return $this->unity;
    }
}

