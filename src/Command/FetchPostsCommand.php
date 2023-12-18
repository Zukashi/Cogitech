<?php

namespace App\Command;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:fetch-posts',
    description: 'Add a short description for your command',
)]
class FetchPostsCommand extends Command
{

    protected static $defaultName = 'app:fetch-posts';

    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;


    public function __construct(HttpClientInterface $httpClient,EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Fetch posts from the API
        $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/posts');
        $postsData = $response->toArray();

        foreach ($postsData as $postData) {
            // Fetch or create the user
            $user = $this->userRepository->find($postData['userId']);
            if (!$user) {
                $user = new User();
                $user->setId($postData['userId']);
                // Additional user data fetching and setting can be done here
                $this->entityManager->persist($user);
            }

            // Create and populate the post entity
            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setBody($postData['body']);
            $post->setUser($user);

            // Persist the post entity
            $this->entityManager->persist($post);
        }

        // Flush to save everything to the database
        $this->entityManager->flush();

        $output->writeln('Posts fetched and processed successfully.');

        return Command::SUCCESS;
    }

}
