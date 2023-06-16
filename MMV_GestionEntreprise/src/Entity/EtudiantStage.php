<?php

namespace App\Entity;

use App\Repository\EtudiantStageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtudiantStageRepository::class)
 */
class EtudiantStage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="stage")
     */
    private $etudiant;

    /**
     * @ORM\ManyToOne(targetEntity=Stage::class, inversedBy="etudiant")
     */
    private $stage;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $annee;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getStage(): ?Stage
    {
        return $this->stage;
    }

    public function setStage(?Stage $stage): self
    {
        $this->stage = $stage;

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
