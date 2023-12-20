<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: '`author`')]
class Author
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]

    private ?int $id;


    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    private $posts;
    #[ORM\Column(type: 'string', length: 255)]

    private ?string $name;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setId(mixed $userId): self
    {
        $this->id = $userId;

        return $this;
    }

}

