<?php

namespace App\Service;

use AllowDynamicProperties;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

    #[AllowDynamicProperties] class PostFetcherService
    {
        private HttpClientInterface $httpClient;
        private EntityManagerInterface $entityManager;
        private AuthorRepository $authorRepository;
        private AuthorService $authorService;
        private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository, AuthorService $authorService, HttpClientInterface $httpClient, EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->authorRepository = $authorRepository;
        $this->postRepository = $postRepository;
        $this->authorService = $authorService;
    }

    public function fetchAndStorePosts(): array
    {
        $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/posts');
        $postsData = $response->toArray();

        foreach ($postsData as $postData) {
            // Fetch or create the user
            $existingPost = $this->postRepository->find($postData['id']);


            if (!$existingPost) {
                $user = $this->authorRepository->find($postData['userId']);
                if (!$user) {
                    $user = $this->authorService->createAuthor($postData['userId']);
                }
                $post = new Post();
                $post->setId($postData['id']);
                $post->setTitle($postData['title']);
                $post->setBody($postData['body']);
                $post->setAuthor($user);
                $this->entityManager->persist($post);
                $this->entityManager->flush();
        }
}
        // Flush to save everything to the database
        $allPosts = $this->postRepository->findAll();

        return $allPosts;
    }
}