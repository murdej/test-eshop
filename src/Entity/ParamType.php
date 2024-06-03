<?php

namespace App\Entity;

use App\Repository\ParamTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParamTypeRepository::class)]
class ParamType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(columnDefinition: "ENUM('number','numbering','color')")]
    private ?ParamTypeType $type = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 64)]
    private ?string $unit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): ?ParamTypeType
    {
        return $this->type;
    }

    public function setType(ParamTypeType $type): static
    {
        $this->type = $type;

        return $this;
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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }
}

enum ParamTypeType: string {
    case Number = 'number';
    case Numbering = 'numbering';
    case Color = 'color';
}