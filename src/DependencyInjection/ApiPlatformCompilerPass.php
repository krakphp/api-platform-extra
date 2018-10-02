<?php

namespace Krak\ApiPlatformExtra\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApiPlatformCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container) {
        $container->getDefinition("api_platform.serializer.normalizer.item")->setClass('Krak\ApiPlatformExtra\Serializer\ConstructorResourceItemNormalizer');
        $container->getDefinition("api_platform.jsonld.normalizer.item")->setClass('Krak\ApiPlatformExtra\Serializer\JsonLdConstructorResourceItemNormalizer');
    }
}
