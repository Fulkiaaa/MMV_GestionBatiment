<?php

namespace App\Entity;

use App\Repository\EntrepriseStageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntrepriseStageRepository::class)
 */
class EntrepriseStage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Stage::class, inversedBy="entreprise")
     */
    private $stage;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="stage")
     */
    private $entreprise;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }
}
