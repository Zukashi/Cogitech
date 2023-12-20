<?php

namespace App\Service;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthorService
{
    private EntityManagerInterface $entityManager;
    private HttpClientInterface $httpClient;
    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
    }

    public function createAuthor($authorId): Author
    {
        $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/users/' . $authorId);
        $userData = $response->toArray();
        $author = new Author();
        $author->setName($userData['name']);
        $author->setId($authorId);
        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return $author;
    }
}
