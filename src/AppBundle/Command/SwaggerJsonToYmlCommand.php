<?php

namespace AppBundle\Command;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class SwaggerJsonToYmlCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:swageer-json-to-yml')
            ->setDescription('Transform your swagger jsons to a yml file')
            ->addArgument('input-folder', InputArgument::REQUIRED, 'The folder containing the json files')
            ->addArgument('output-file', InputArgument::REQUIRED, 'The yml output file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../../'.$input->getArgument('input-folder'))->exclude('api-docs.json');

        $content = [];
        foreach ($finder as $file) {
            $content[] = json_decode($file->getContents(), true);
        }

        dump($content);
        $yaml = Yaml::dump($content);
        file_put_contents(__DIR__.'/../../../'.$input->getArgument('output-file'), $yaml);
    }
}