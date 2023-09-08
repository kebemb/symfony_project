<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BienRepository::class)]
class Bien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(
        min: 10,
        max: 50,
        minMessage: 'Le nom du bien doit etre de  {{ limit }} characters minimum',
        maxMessage: 'Le nom du bien doit etre de{{ limit }} characters maximum',
    )]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDispo = null;

    #[ORM\Column]
    private ?bool $avecJardin = null;

    #[ORM\ManyToOne(inversedBy: 'biens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ville $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoPrincipal = null;

    #[ORM\ManyToOne(inversedBy: 'biens')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'bien', targetEntity: BienUser::class)]
    private Collection $bienUsers;

    public function __construct()
    {
        $this->bienUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDispo(): ?\DateTimeInterface
    {
        return $this->dateDispo;
    }

    public function setDateDispo(\DateTimeInterface $dateDispo): static
    {
        $this->dateDispo = $dateDispo;

        return $this;
    }

    public function isAvecJardin(): ?bool
    {
        return $this->avecJardin;
    }

    public function setAvecJardin(bool $avecJardin): static
    {
        $this->avecJardin = $avecJardin;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPhotoPrincipal(): ?string
    {
        return $this->photoPrincipal;
    }

    public function setPhotoPrincipal(?string $photoPrincipal): static
    {
        $this->photoPrincipal = $photoPrincipal;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, BienUser>
     */
    public function getBienUsers(): Collection
    {
        return $this->bienUsers;
    }

    public function addBienUser(BienUser $bienUser): static
    {
        if (!$this->bienUsers->contains($bienUser)) {
            $this->bienUsers->add($bienUser);
            $bienUser->setBien($this);
        }

        return $this;
    }

    public function removeBienUser(BienUser $bienUser): static
    {
        if ($this->bienUsers->removeElement($bienUser)) {
            // set the owning side to null (unless already changed)
            if ($bienUser->getBien() === $this) {
                $bienUser->setBien(null);
            }
        }

        return $this;
    }
}
