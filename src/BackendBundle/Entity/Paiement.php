<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\PaiementRepository")
 */
class Paiement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="clientName", type="string", length=255)
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="paiementType", type="string", length=255)
     */
    private $paiementType;

    /**
     * @var string
     *
     * @ORM\Column(name="rib", type="string", length=255)
     */
    private $rib;

    /**
     * @var string
     *
     * @ORM\Column(name="numCheque", type="string", length=255)
     */
    private $numCheque;


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
     * Set reference
     *
     * @param string $reference
     *
     * @return Paiement
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     *
     * @return Paiement
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set paiementType
     *
     * @param string $paiementType
     *
     * @return Paiement
     */
    public function setPaiementType($paiementType)
    {
        $this->paiementType = $paiementType;

        return $this;
    }

    /**
     * Get paiementType
     *
     * @return string
     */
    public function getPaiementType()
    {
        return $this->paiementType;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return Paiement
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set numCheque
     *
     * @param string $numCheque
     *
     * @return Paiement
     */
    public function setNumCheque($numCheque)
    {
        $this->numCheque = $numCheque;

        return $this;
    }

    /**
     * Get numCheque
     *
     * @return string
     */
    public function getNumCheque()
    {
        return $this->numCheque;
    }
}

