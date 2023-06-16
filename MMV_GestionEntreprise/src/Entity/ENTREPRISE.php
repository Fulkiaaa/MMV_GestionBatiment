<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
class Entreprise
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
    private $rs;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $specialite;

    /**
     * @ORM\OneToMany(targetEntity=Employe::class, mappedBy="entreprise")
     */
    private $employes;

    /**
     * @ORM\OneToMany(targetEntity=EntrepriseStage::class, mappedBy="entreprise")
     */
    private $stage;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
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

    public function getRs(): ?string
    {
        return $this->rs;
    }

    public function setRs(?string $rs): self
    {
        $this->rs = $rs;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(?int $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

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
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): self
    {
        if (!$this->employes->contains($employe)) {
            $this->employes[] = $employe;
            $employe->setEntreprise($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getEntreprise() === $this) {
                $employe->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EntrepriseStage>
     */
    public function getStage(): Collection
    {
        return $this->stage;
    }

    public function addStage(EntrepriseStage $stage): self
    {
        if (!$this->stage->contains($stage)) {
            $this->stage[] = $stage;
            $stage->setEntreprise($this);
        }

        return $this;
    }

    public function removeStage(EntrepriseStage $stage): self
    {
        if ($this->stage->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getEntreprise() === $this) {
                $stage->setEntreprise(null);
            }
        }

        return $this;
    }
}
