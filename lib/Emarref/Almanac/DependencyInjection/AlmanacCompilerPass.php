<?php

namespace Emarref\Almanac\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Emarref\Almanac\Destination\DestinationManager;
use Emarref\Almanac\Filter\FilterManager;
use Emarref\Almanac\Renderer\RendererManager;
use Emarref\Almanac\Source\SourceManager;
use Emarref\Almanac\Statistic\StatisticManager;

class AlmanacCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->manageDestinations($container);
        $this->manageFilters($container);
        $this->manageRenderers($container);
        $this->manageSources($container);
        $this->manageStatistics($container);
    }

    public function manageDestinations(ContainerBuilder $container)
    {
        /** @var DestinationManager $manager */
        $manager = $container->get('almanac.manager.destination');

        $this->manage($container, 'destination', $manager);
    }

    public function manageFilters(ContainerBuilder $container)
    {
        /** @var FilterManager $manager */
        $manager = $container->get('almanac.manager.filter');

        $this->manage($container, 'filter', $manager);
    }

    public function manageRenderers(ContainerBuilder $container)
    {
        /** @var RendererManager $manager */
        $manager = $container->get('almanac.manager.renderer');

        $this->manage($container, 'renderer', $manager);
    }

    public function manageSources(ContainerBuilder $container)
    {
        /** @var SourceManager $manager */
        $manager = $container->get('almanac.manager.source');

        $this->manage($container, 'source', $manager);
    }

    public function manageStatistics(ContainerBuilder $container)
    {
        /** @var StatisticManager $manager */
        $manager = $container->get('almanac.manager.statistic');

        $this->manage($container, 'statistic', $manager);
    }

    protected function manage(ContainerBuilder $container, $tag_name, AbstractManager $manager)
    {
        $definitions = $container->findTaggedServiceIds(sprintf('almanac.%s', $tag_name));

        if (0 < count($definitions)) {
            foreach ($definitions as $name => $attributes) {
                $manager->manageItem($name, $attributes[0]);
            }
        }
    }
}