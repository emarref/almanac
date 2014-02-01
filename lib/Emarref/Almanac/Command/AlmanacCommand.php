<?php

namespace Emarref\Almanac\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AlmanacCommand extends Command implements CommandInterface
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    public function getContainer()
    {
        if (!isset($this->container)) {
            $this->setContainer(new ContainerBuilder());
        }

        return $this->container;
    }

    public function setContainer(ContainerBuilder $container)
    {
        $this->container = $container;
    }
}