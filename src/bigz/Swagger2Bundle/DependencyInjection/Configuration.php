<?php

namespace bigz\Swagger2Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bigz_swagger2');

        $rootNode
            ->children()
                ->scalarNode('swagger')->defaultValue('2.0')->end()
                ->arrayNode('info')
                    ->children()
                        ->scalarNode('title')->defaultValue('My API')->end()
                        ->scalarNode('description')->defaultValue('An awesome API using swagger 2')->end()
                        ->scalarNode('termsOfService')->end()
                        ->scalarNode('version')->defaultValue('0.1')->end()
                    ->end()
                ->end()
                ->scalarNode('host')->defaultValue('api.yourhost.com')->end()
                ->arrayNode('schemes')
                    ->defaultValue(['http', 'https'])
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('basePath')->defaultValue('/v1')->end()
                ->arrayNode('produces')
                    ->defaultValue(['application/json'])
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('security')
                    ->children()
                        ->enumNode('type')
                            ->values(array(null, 'basic', 'apiKey', 'oauth2'))
                            ->defaultValue(null)
                        ->end()
                        ->enumNode('in')
                            ->values(array(null, 'query', 'header'))
                            ->defaultValue('header')
                        ->end()
                        ->scalarNode('name')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
