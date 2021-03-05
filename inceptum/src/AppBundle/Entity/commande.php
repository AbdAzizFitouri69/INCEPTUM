<?php

namespace AppBundle\Entity;

use CategorieBundle\Entity\categorie;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Email;

/**
 * commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\commandeRepository")
 */
class commande
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateAjout", type="datetime")
     */
    private $dateAjout;

    /**
     * @var int
     *
     * @ORM\Column(name="idClient", type="integer")
     *
     *
     */
    private $idClient;


    /**
     * @var string
     *
     * @ORM\Column(name="nomProprietaire", type="string", length=255)
     */
    private $nomProprietaire;

    /**
     * @var string
     *
     * @ORM\Column(name="emailProprietaire", type="string", length=255)
     */
    private $emailProprietaire;

    /**
     * @var string
     *
     * @ORM\Column(name="addProprietaire", type="string", length=255)
     */
    private $addProprietaire;

    /**
     * @var string
     *
     * @ORM\Column(name="telProprietaire", type="string", length=255)
     */
    private $telProprietaire;


    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;


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
     * Get idClient
     *
     * @return int
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * Set idClient
     *
     * @param int $idClient
     *
     * @return commande
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     *
     * @return commande
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }


    /**
     * Get dateAjout
     *
     * @return \DateTime
    */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }



    /**
     * Set nomProprietaire
     *
     * @param string $nomProprietaire
     *
     * @return commande
     */
    public function setNomProprietaire($nomProprietaire)
    {
        $this->nomProprietaire = $nomProprietaire;

        return $this;
    }

    /**
     * Get nomProprietaire
     *
     * @return string
     */
    public function getNomProprietaire()
    {
        return $this->nomProprietaire;
    }


    /**
     * Set addProprietaire
     *
     * @param string $addProprietaire
     *
     * @return commande
     */
    public function setAddProprietaire($addProprietaire)
    {
        $this->addProprietaire = $addProprietaire;

        return $this;
    }

    /**
     * Get addProprietaire
     *
     * @return string
     */
    public function getAddProprietaire()
    {
        return $this->addProprietaire;
    }



    /**
     * Set telProprietaire
     *
     * @param string $telProprietaire
     *
     * @return commande
     */
    public function setTelProprietaire($telProprietaire)
    {
        $this->telProprietaire = $telProprietaire;

        return $this;
    }

    /**
     * Get telProprietaire
     *
     * @return string
     */
    public function getTelProprietaire()
    {
        return $this->telProprietaire;
    }


    /**
     * Set emailProprietaire
     *
     * @param string $emailProprietaire
     *
     * @return commande
     */
    public function setEmailProprietaire($emailProprietaire)
    {
        $this->emailProprietaire = $emailProprietaire;

        return $this;
    }

    /**
     * Get emailProprietaire
     *
     * @return string
     */
    public function getEmailProprietaire()
    {
        return $this->emailProprietaire;
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
     * Set etat
     *
     * @param string $etat
     *
     * @return commande
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }


}

