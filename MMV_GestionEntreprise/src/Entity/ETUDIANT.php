<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtudiantRepository::class)
 */
class Etudiant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $specialite;

    /**
     * @ORM\OneToMany(targetEntity=EtudiantStage::class, mappedBy="etudiant")
     */
    private $stage;

    public function __construct()
    {
        $this->stage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection<int, EtudiantStage>
     */
    public function getStage(): Collection
    {
        return $this->stage;
    }

    public function addStage(EtudiantStage $stage): self
    {
        if (!$this->stage->contains($stage)) {
            $this->stage[] = $stage;
            $stage->setEtudiant($this);
        }

        return $this;
    }

    public function removeStage(EtudiantStage $stage): self
    {
        if ($this->stage->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getEtudiant() === $this) {
                $stage->setEtudiant(null);
            }
        }

        return $this;
    }
}
