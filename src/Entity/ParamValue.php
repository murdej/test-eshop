<?php

namespace App\Entity;

use App\Repository\ParamValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParamValueRepository::class)]
class ParamValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $numberValue = null;

    #[ORM\Column(length: 4096, nullable: true)]
    private ?string $stringValue = null;

    #[ORM\ManyToOne(inversedBy: 'param_types')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParamType $paramType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNumberValue(): ?float
    {
        return $this->numberValue;
    }

    public function setNumberValue(?float $numberValue): static
    {
        $this->numberValue = $numberValue;

        return $this;
    }

    public function getStringValue(): ?string
    {
        return $this->stringValue;
    }

    public function setStringValue(?string $stringValue): static
    {
        $this->stringValue = $stringValue;

        return $this;
    }

    public function getParamType(): ?ParamType
    {
        return $this->paramType;
    }

    public function setParamType(?ParamType $paramType): static
    {
        $this->paramType = $paramType;

        return $this;
    }
}
