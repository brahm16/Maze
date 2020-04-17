<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\FactureRepository")
 */
class Facture
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
     * @ORM\Column(name="clientType", type="string", length=255)
     */
    private $clientType;

    /**
     * @var string
     *
     * @ORM\Column(name="type_facture", type="string", length=255)
     */
    private $typeFacture;

    /**
     * @var string
     *
     * @ORM\Column(name="statutFacture", type="string", length=255)
     */
    private $statutFacture;

    /**
     * @var float
     *
     * @ORM\Column(name="totalHT", type="float")
     */
    private $totalHT;

    /**
     * @var float
     *
     * @ORM\Column(name="totalTTC", type="float")
     */
    private $totalTTC;

    /**
     * @var string
     *
     * @ORM\Column(name="echeance", type="string", length=255)
     */
    private $echeance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFact", type="datetime")
     */
    private $dateFact;

    /**
     * @ORM\ManyToOne(targetEntity="Achat")
     * @ORM\JoinColumn(name="achat_id", referencedColumnName="id")
     * */
    private $achat;




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
     * Set achat
     *
     * @param string $achat
     *
     * @return Achat
     */
    public function setAchat($achat)
    {
        $this->achat = $achat;

        return $this;
    }

    /**
     * Get achat
     *
     * @return string
     */
    public function getAchat()
    {
        return $this->achat;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Facture
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
     * @return Facture
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
     * Set clientType
     *
     * @param string $clientType
     *
     * @return Facture
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;

        return $this;
    }

    /**
     * Get clientType
     *
     * @return string
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * Set typeFacture
     *
     * @param string $typeFacture
     *
     * @return Facture
     */
    public function setTypeFacture($typeFacture)
    {
        $this->typeFacture = $typeFacture;

        return $this;
    }

    /**
     * Get typeFacture
     *
     * @return string
     */
    public function getTypeFacture()
    {
        return $this->typeFacture;
    }

    /**
     * Set statutFacture
     *
     * @param string $statutFacture
     *
     * @return Facture
     */
    public function setStatutFacture($statutFacture)
    {
        $this->statutFacture = $statutFacture;

        return $this;
    }

    /**
     * Get statutFacture
     *
     * @return string
     */
    public function getStatutFacture()
    {
        return $this->statutFacture;
    }

    /**
     * Set totalHT
     *
     * @param string $totalHT
     *
     * @return Facture
     */
    public function setTotalHT($totalHT)
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    /**
     * Get totalHT
     *
     * @return string
     */
    public function getTotalHT()
    {
        return $this->totalHT;
    }

    /**
     * Set totalTTC
     *
     * @param float $totalTTC
     *
     * @return Facture
     */
    public function setTotalTTC($totalTTC)
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    /**
     * Get totalTTC
     *
     * @return float
     */
    public function getTotalTTC()
    {
        return $this->totalTTC;
    }

    /**
     * Set echeance
     *
     * @param string $echeance
     *
     * @return Facture
     */
    public function setEcheance($echeance)
    {
        $this->echeance = $echeance;

        return $this;
    }

    /**
     * Get echeance
     *
     * @return string
     */
    public function getEcheance()
    {
        return $this->echeance;
    }

    /**
     * Set dateFact
     *
     * @param \DateTime $dateFact
     *
     * @return Facture
     */
    public function setDateFact($dateFact)
    {
        $this->dateFact = $dateFact;

        return $this;
    }

    /**
     * Get dateFact
     *
     * @return \DateTime
     */
    public function getDateFact()
    {
        return $this->dateFact;
    }
}

