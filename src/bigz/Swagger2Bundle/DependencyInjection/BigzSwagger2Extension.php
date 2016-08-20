<?php

namespace bigz\Swagger2Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class BigzSwagger2Extension
 * @package bigz\Swagger2Bundle\DependencyInjection
 */
class BigzSwagger2Extension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bigz_swagger2.swagger', $config['swagger']);
        $container->setParameter('bigz_swagger2.info.title', $config['info']['title']);
        $container->setParameter('bigz_swagger2.info.description', $config['info']['title']);
        $container->setParameter('bigz_swagger2.info.version', $config['info']['title']);
        $container->setParameter('bigz_swagger2.host', $config['host']);
        $container->setParameter('bigz_swagger2.schemes', $config['schemes']);
        $container->setParameter('bigz_swagger2.base_path', $config['basePath']);
        $container->setParameter('bigz_swagger2.produces', $config['produces']);
        $container->setParameter('bigz_swagger2.security', $config['security']);

        $container->setParameter('bigz_swagger2.config', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
