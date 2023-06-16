<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=EtudiantStage::class, mappedBy="stage")
     */
    private $etudiant;

    /**
     * @ORM\OneToMany(targetEntity=EntrepriseStage::class, mappedBy="stage")
     */
    private $entreprise;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->etudiant = new ArrayCollection();
        $this->entreprise = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, EtudiantStage>
     */
    public function getEtudiant(): Collection
    {
        return $this->etudiant;
    }

    public function addEtudiant(EtudiantStage $etudiant): self
    {
        if (!$this->etudiant->contains($etudiant)) {
            $this->etudiant[] = $etudiant;
            $etudiant->setStage($this);
        }

        return $this;
    }

    public function removeEtudiant(EtudiantStage $etudiant): self
    {
        if ($this->etudiant->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getStage() === $this) {
                $etudiant->setStage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EntrepriseStage>
     */
    public function getEntreprise(): Collection
    {
        return $this->entreprise;
    }

    public function addEntreprise(EntrepriseStage $entreprise): self
    {
        if (!$this->entreprise->contains($entreprise)) {
            $this->entreprise[] = $entreprise;
            $entreprise->setStage($this);
        }

        return $this;
    }

    public function removeEntreprise(EntrepriseStage $entreprise): self
    {
        if ($this->entreprise->removeElement($entreprise)) {
            // set the owning side to null (unless already changed)
            if ($entreprise->getStage() === $this) {
                $entreprise->setStage(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
