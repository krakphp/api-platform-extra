<?php

namespace Krak\ApiPlatformExtra;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiPlatformExtraBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        $container->addCompilerPass(new DependencyInjection\ApiPlatformCompilerPass());
    }
}
