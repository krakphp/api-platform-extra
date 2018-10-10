<?php

namespace Krak\ApiPlatformExtra\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('api_platform_extra');

        $rootNode
            ->children()
                ->booleanNode('enable_message_bus_data_persister')
                    ->defaultTrue()
                ->end()
                ->booleanNode('enable_operation_resource_class')
                    ->defaultTrue()
                ->end()
                ->booleanNode('enable_constructor_deserialization')
                    ->defaultTrue()
                ->end()
                ->booleanNode('enable_overriding_annotation_property_metadata_factory')
                    ->defaultTrue()
                ->end()
                ->scalarNode('additional_swagger_path')
                    ->defaultValue('%kernel.project_dir%/config/api_platform/swagger.yaml')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
