<?php

namespace Krak\ApiPlatformExtra\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class ApiPlatformExtraExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container) {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        
        $config = $this->processConfiguration(new Configuration(), $configs);


        $projectDir = $container->getParameter('kernel.project_dir');
        $swaggerFilePath = $projectDir . '/config/api_platform/swagger.yaml';
        $swaggerDocs = [];

        if (\file_exists($swaggerFilePath)) {
            $container->addResource(new FileResource($swaggerFilePath));
            $swaggerDocs = Yaml::parseFile($swaggerFilePath);
        }

        $container->findDefinition('Krak\ApiPlatformExtra\Documentation\Serializer\AdditionalSwaggerNormalizer')
            ->setArgument('$swaggerDocs', $swaggerDocs);

        if (!$container->has('message_bus')) {
            $container->removeDefinition('Krak\ApiPlatformExtra\DataPersister\MessageBusDataPersister');
        }
    }
}
