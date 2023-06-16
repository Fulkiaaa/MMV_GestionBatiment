<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeRepository::class)
 */
class Employe
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $fonction;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="employes")
     */
    private $entreprise;

    /**
     * @ORM\OneToMany(targetEntity=EmployeProfil::class, mappedBy="employe")
     */
    private $leprofil;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tel;

    public function __construct()
    {
        $this->leprofil = new ArrayCollection();
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

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): self
    {
        $this->fonction = $fonction;

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

    /**
     * @return Collection<int, EmployeProfil>
     */
    public function getLeprofil(): Collection
    {
        return $this->leprofil;
    }

    public function addLeprofil(EmployeProfil $leprofil): self
    {
        if (!$this->leprofil->contains($leprofil)) {
            $this->leprofil[] = $leprofil;
            $leprofil->setEmploye($this);
        }

        return $this;
    }

    public function removeLeprofil(EmployeProfil $leprofil): self
    {
        if ($this->leprofil->removeElement($leprofil)) {
            // set the owning side to null (unless already changed)
            if ($leprofil->getEmploye() === $this) {
                $leprofil->setEmploye(null);
            }
        }

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }
}
