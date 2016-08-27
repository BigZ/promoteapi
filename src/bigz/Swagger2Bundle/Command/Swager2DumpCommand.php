<?php

namespace bigz\Swagger2Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class Swager2DumpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swagger2:dump')
            ->setDescription('Create a swagger2 definition file from your configuration')
            ->addArgument('output-file', InputArgument::OPTIONAL, 'The path to the dumped file', 'swagger.yml')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $extractor = $container->get('nelmio_api_doc.extractor.api_doc_extractor');
        $container->get('bigz_swagger2.formatter')->setConfig($container->getParameter('bigz_swagger2.config'));
        $content = $container->get('bigz_swagger2.formatter')->format($extractor->all());
        $contentYaml = Yaml::dump($content, 10);
        // dirty fix symfony bug
        // See https://github.com/symfony/symfony/issues/15781
        $contentYaml = str_replace(': {  }', ': []', $contentYaml);

        file_put_contents(
            __DIR__.'/../../../../'.$input->getArgument('output-file'),
            $contentYaml
        );
    }
}
