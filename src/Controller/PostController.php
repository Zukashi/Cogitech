<?php

namespace App\Controller;
use App\Repository\PostRepository;

use App\Service\PostFetcherService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private PostFetcherService $postFetcher;

    public function __construct(PostFetcherService $postFetcher)
    {
        $this->postFetcher = $postFetcher;
    }

    #[Route('/posts', name: 'app_post')]
    public function index(LoggerInterface $logger): Response
    {
        $posts = $this->postFetcher->fetchAndStorePosts();
        $logger->info('Posts variable:', ['posts' => $posts]);
        return $this->render('post/index.html.twig', [
            'posts' => $posts, // Pass posts to the template
        ]);
    }


    #[Route("/delete/{id}", name:"delete_post")]

    public function delete(int $id, PostRepository $postRepository, EntityManagerInterface $entityManager): Response
    {
        $post = $postRepository->findPostWithAuthor($id);

        if (!$post) {
            // Handle the case where the post doesn't exist
            return $this->redirectToRoute('app_post_list');
        }

        $entityManager->remove($post);
        $entityManager->flush();

        // Redirect to the list page after deletion
        return $this->redirectToRoute('app_post_list'); // Replace 'post_list' with your list route name
    }
}
