<?php

namespace Krak\ApiPlatformExtra\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder('api_platform_extra');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('enable_message_bus_data_persister')
                    ->defaultFalse()
                ->end()
                ->booleanNode('enable_operation_resource_class')
                    ->defaultFalse()
                ->end()
                ->booleanNode('enable_constructor_deserialization')
                    ->defaultFalse()
                ->end()
                ->booleanNode('enable_overriding_annotation_property_metadata_factory')
                    ->defaultFalse()
                ->end()
                ->scalarNode('additional_swagger_path')
                    ->defaultValue('%kernel.project_dir%/config/api_platform/swagger.yaml')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
