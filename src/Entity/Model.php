<?php

namespace App\Entity;

use App\Entity\Traits\DateTimeTrait;
use App\Repository\ModelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_MARQUE', fields: ['name'])]
#[UniqueEntity(fields: ['name'], message: "Le model est déjà utilisé, veuillez en choisir une autre !")]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    use DateTimeTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $enable = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): static
    {
        $this->enable = $enable;

        return $this;
    }
}
