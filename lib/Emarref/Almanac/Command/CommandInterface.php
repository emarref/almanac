<?php

namespace Emarref\Almanac\Command;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface CommandInterface
{
    /**
     * @return ContainerBuilder
     */
    public function getContainer();

    /**
     * @param ContainerBuilder $container
     */
    public function setContainer(ContainerBuilder $container);
}