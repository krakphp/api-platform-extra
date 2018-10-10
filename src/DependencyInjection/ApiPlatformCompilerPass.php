<?php

namespace Krak\ApiPlatformExtra\DependencyInjection;

use Krak\ApiPlatformExtra\Documentation\Serializer\OperationResourceClassDocumentationNormalizer;
use Krak\ApiPlatformExtra\Metadata\Property\OverridingAnnotationPropertyMetadataFactory;
use Krak\ApiPlatformExtra\Routing\ApiPlatformExtraLoader;
use Krak\ApiPlatformExtra\Serializer\ConstructorResourceItemNormalizer;
use Krak\ApiPlatformExtra\Serializer\JsonLdConstructorResourceItemNormalizer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApiPlatformCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container) {
        if ($container->getParameter('api_platform_extra._enable_constructor_deserialization')) {
            $container->getDefinition('api_platform.serializer.normalizer.item')->setClass(ConstructorResourceItemNormalizer::class);
            $container->getDefinition('api_platform.jsonld.normalizer.item')->setClass(JsonLdConstructorResourceItemNormalizer::class);
        }
        if ($container->getParameter('api_platform_extra._enable_operation_resource_class')) {
            $container->getDefinition('api_platform.route_loader')->setClass(ApiPlatformExtraLoader::class);
            $container->getDefinition('api_platform.swagger.normalizer.documentation')->setClass(OperationResourceClassDocumentationNormalizer::class);
        }
        if ($container->getParameter('api_platform_extra._enable_overriding_annotation_property_metadata_factory')) {
            $container->getDefinition('api_platform.metadata.property.metadata_factory.annotation')
                ->setClass(OverridingAnnotationPropertyMetadataFactory::class)
                ->setDecoratedService('api_platform.metadata.property.metadata_factory', null, -10);
        }
    }
}
