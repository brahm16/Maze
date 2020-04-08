<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\ReclamationRepository")
 */
class Reclamation
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
 * @var int
 *
 * @ORM\Column(name="idUser", type="integer")
 */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="user_mail", type="string", length=255)
     */
    private $userMail;

    /**
     * @return string
     */
    public function getUserMail()
    {
        return $this->userMail;
    }

    /**
     * @param string $userMail
     */
    public function setUserMail($userMail)
    {
        $this->userMail = $userMail;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param int $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="idProduct",referencedColumnName="id_product")
     */
    private $idProduct;

    /**
     * @var string
     * @Assert\Length(min=5,max=255,minMessage="longueur minimal 5 caractère!")
     * @ORM\Column(name="contenu", type="string", length=1000)
     * @Assert\NotNull(message="Ce champs est obligatoire!")
     */
    private $contenu;

    /**
     * @var TextareaFormField
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;

    /**
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Priority")
     * @ORM\JoinColumn(name="priority",referencedColumnName="id")
     */
    private $priority;


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
     * Set idProduct
     *
     * @param integer $idProduct
     *
     * @return Reclamation
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * Get idProduct
     *
     * @return int
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Reclamation
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Reclamation
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reclamation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set priority
     *
     * @param string $priority
     *
     * @return Reclamation
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }
}

