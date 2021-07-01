<?php

namespace App\Entity;

use App\Repository\FormmapRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormmapRepository::class)
 */
class Formmap
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="json", length=300000, nullable=true)
     */
    private $map;

    /**
     * @ORM\Column(type="integer")
     */
    private $formid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMap(): ?string
    {
        return $this->map;
    }

    public function setMap(?string $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getformid(): ?int
    {
        return $this->formid;
    }

    public function setformid(int $formid): self
    {
        $this->formid = $formid;

        return $this;
    }
}
