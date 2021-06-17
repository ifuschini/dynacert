<?php

namespace App\Entity;

use App\Repository\FormpdfimageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormpdfimageRepository::class)
 */
class Formpdfimage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=300000, nullable=true)
     */
    private $images;

    /**
     * @ORM\Column(type="integer")
     */
    private $formid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getFormId(): ?int
    {
        return $this->formid;
    }

    public function setFormId(int $formid): self
    {
        $this->formid = $formid;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
