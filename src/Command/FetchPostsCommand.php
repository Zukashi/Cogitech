<?php

namespace App\Command;

use App\Service\PostFetcherService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fetch-posts',
    description: 'Add a short description for your command',
)]
class FetchPostsCommand extends Command
{

    protected static $defaultName = 'app:fetch-posts';



    private PostFetcherService $postFetcherService;
    public function __construct(PostFetcherService $postFetcherService)
    {

        $this->postFetcherService = $postFetcherService;
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
        $posts = $this->postFetcherService->fetchAndStorePosts();
        foreach ($posts as $post) {
            // Use the getter methods provided in the Post entity
            $output->writeln('Post ID: ' . $post->getId() . ', Title: ' . $post->getTitle());
        }


        $output->writeln('Posts fetched and processed successfully.');

        return Command::SUCCESS;
    }

}
