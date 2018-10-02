<?php

namespace Krak\ApiPlatformExtra;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiPlatformExtraBundle extends Bundle
{
    public function getContainerExtension() {
        return new DependencyInjection\ApiPlatformUtilExtension();
    }

    public function build(ContainerBuilder $container) {
        $container->addCompilerPass(new DependencyInjection\ApiPlatformCompilerPass());
    }
}
