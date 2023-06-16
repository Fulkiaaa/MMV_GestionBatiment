<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 */
class Profil
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
     * @ORM\OneToMany(targetEntity=EmployeProfil::class, mappedBy="profil")
     */
    private $employe;

    public function __construct()
    {
        $this->employe = new ArrayCollection();
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

    /**
     * @return Collection<int, EmployeProfil>
     */
    public function getEmploye(): Collection
    {
        return $this->employe;
    }

    public function addEmploye(EmployeProfil $employe): self
    {
        if (!$this->employe->contains($employe)) {
            $this->employe[] = $employe;
            $employe->setProfil($this);
        }

        return $this;
    }

    public function removeEmploye(EmployeProfil $employe): self
    {
        if ($this->employe->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getProfil() === $this) {
                $employe->setProfil(null);
            }
        }

        return $this;
    }
}
