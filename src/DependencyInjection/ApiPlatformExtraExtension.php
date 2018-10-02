<?php

namespace Krak\ApiPlatformExtra\DependencyInjection;

use Krak\ApiPlatformExtra\DataPersister\MessageBusDataPersister;
use Krak\ApiPlatformExtra\Documentation\Serializer\AdditionalSwaggerNormalizer;
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
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container) {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->registerAdditionalSwaggerNormalizer($container, $config);
        $this->registerDataPersister($container, $config);
        $container->setParameter("api_platform_extra._enable_operation_resource_class", $config['enable_operation_resource_class']);
        $container->setParameter("api_platform_extra._enable_constructor_deserialization", $config['enable_constructor_deserialization']);
    }

    private function registerAdditionalSwaggerNormalizer(ContainerBuilder $container, array $config) {
        if (!\file_exists($config['additional_swagger_path'])) {
            return;
        }

        $container->addResource(new FileResource($config['additional_swagger_path']));
        $container->register(AdditionalSwaggerNormalizer::class)
            ->setDecoratedService('api_platform.swagger.normalizer.documentation')
            ->setArguments([
                '@Krak\ApiPlatformExtra\Documentation\Serializer\AdditionalSwaggerNormalizer.inner',
                Yaml::parseFile($config['additional_swagger_path'])
            ])
            ->addTag('serializer.normalizer', ['priority' => 18]);
    }

    private function registerDataPersister(ContainerBuilder $container, array $config) {
        if (!$container->has('message_bus') || !$config['enable_message_bus_data_persister']) {
            return;
        }

        $container->register(MessageBusDataPersister::class)
            ->setArguments([
                '@message_bus'
            ])
            ->addTag('api_platform.data_persister', ['priority' => -10]);
    }
}
