<?php

namespace App\Entity;

use App\Repository\EmployeProfilRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeProfilRepository::class)
 */
class EmployeProfil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="leprofil")
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="employe")
     */
    private $profil;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $annee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(?\DateTimeInterface $annee): self
    {
        $this->annee = $annee;

        return $this;
    }
}
