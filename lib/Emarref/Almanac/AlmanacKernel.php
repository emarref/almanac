<?php

namespace Emarref\Almanac;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Emarref\Almanac\DependencyInjection\AlmanacCompilerPass;

class AlmanacKernel
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function initialiseDefaults()
    {
        /*
         * Configure parameters
         */

        // Readers
        $this->container->setParameter('almanac.reader.statistic.class',    'Emarref\Almanac\Reader\StatisticReader');
        $this->container->setParameter('almanac.reader.result.class',       'Emarref\Almanac\Reader\ResultReader');
        $this->container->setParameter('almanac.reader.annotation.class',   'Doctrine\Common\Annotations\AnnotationReader');

        $this->container
            ->register('almanac.reader.statistic', '%almanac.reader.statistic.class%')
            ->addArgument(new Reference('almanac.reader.annotation'));

        $this->container
            ->register('almanac.reader.result', '%almanac.reader.result.class%')
            ->addArgument(new Reference('almanac.reader.annotation'));

        $this->container
            ->register('almanac.reader.annotation', '%almanac.reader.annotation.class%');

        // Managers
        $this->container->setParameter('almanac.manager.builder.class' ,    'Emarref\Almanac\Builder\BuilderManager');
        $this->container->setParameter('almanac.manager.destination.class', 'Emarref\Almanac\Destination\DestinationManager');
        $this->container->setParameter('almanac.manager.filter.class',      'Emarref\Almanac\Filter\FilterManager');
        $this->container->setParameter('almanac.manager.renderer.class',    'Emarref\Almanac\Renderer\RendererManager');
        $this->container->setParameter('almanac.manager.source.class',      'Emarref\Almanac\Source\SourceManager');
        $this->container->setParameter('almanac.manager.statistic.class',   'Emarref\Almanac\Statistic\StatisticManager');

        $this->container
            ->register('almanac.manager.destination', '%almanac.manager.destination.class%')
            ->addArgument($this->container);

        $this->container
            ->register('almanac.manager.filter', '%almanac.manager.filter.class%')
            ->addArgument($this->container);

        $this->container
            ->register('almanac.manager.renderer', '%almanac.manager.renderer.class%')
            ->addArgument($this->container);

        $this->container
            ->register('almanac.manager.source', '%almanac.manager.source.class%')
            ->addArgument($this->container);

        $this->container
            ->register('almanac.manager.statistic', '%almanac.manager.statistic.class%')
            ->addArgument($this->container);

        // Builder
        $this->container->setParameter('almanac.builder.statistic.class',   'Emarref\Almanac\Builder\DefaultStatisticBuilder');

        $this->container
            ->register('almanac.builder.statistic', '%almanac.builder.statistic.class%')
            ->addArgument($this->container);

        // Renderers
        $this->container->setParameter('almanac.renderer.markdown.class',  'Emarref\Almanac\Renderer\MarkdownRenderer');
        $this->container->setParameter('almanac.renderer.json.class',      'Emarref\Almanac\Renderer\JsonRenderer');
        $this->container->setParameter('almanac.renderer.html.class',      'Emarref\Almanac\Renderer\HtmlRenderer');

        $this->configureRenderer('almanac.renderer.html',     '%almanac.renderer.html.class%',     array('alias' => 'html'));
        $this->configureRenderer('almanac.renderer.markdown', '%almanac.renderer.markdown.class%', array('alias' => 'markdown'));
        $this->configureRenderer('almanac.renderer.json',     '%almanac.renderer.json.class%',     array('alias' => 'json'));

        // Filters
        $this->container->setParameter('almanac.filter.table.class',        'Emarref\Almanac\Filter\Data\TableFilter');
        $this->container->setParameter('almanac.filter.average.class',      'Emarref\Almanac\Filter\Data\AverageFilter');

        $this->configureFilter('almanac.filter.table',   '%almanac.filter.table.class%',   array('alias' => 'table'));
        $this->configureFilter('almanac.filter.average', '%almanac.filter.average.class%', array('alias' => 'average'));

        /*
         * Configure annotations
         */

        $annotations_dir = realpath(__DIR__ . '/../..');
        AnnotationRegistry::registerAutoloadNamespace('Emarref\Almanac\Annotation', $annotations_dir);

        /*
         * Trigger compiler pass to find 3rd party instances
         */

        $this->container->addCompilerPass(new AlmanacCompilerPass());
    }

    protected function getDefaultTypeMapping()
    {
        return array(
            'source' => array(
                'mysql' => 'Emarref\Almanac\Source\MysqlSource'
            ),
            'destination' => array(
                'filesystem' => 'Emarref\Almanac\Destination\FilesystemDestination'
            )
        );
    }

    protected function getClassNameForDefaultType($type, $name)
    {
        $mapping = $this->getDefaultTypeMapping();

        return isset($mapping[$type][$name]) ? $mapping[$type][$name] : null;
    }

    protected function configureParameters(array $configuration)
    {
        if (!isset($configuration['parameters']) || !is_array($configuration['parameters'])) {
            return;
        }

        foreach ($configuration['parameters'] as $name => $value) {
            $this->container->setParameter($name, $value);
        }
    }

    protected function configureDestination($name, array $options = array())
    {
        $class_name = null;

        if (isset($options['type'])) {
            $class_name = $this->getClassNameForDefaultType('destination', $options['type']);
        } elseif (isset($options['class'])) {
            $class_name = $options['class'];
        }

        if (!$class_name) {
            throw new \Exception(sprintf('Unable to configure destination "%s"', $name));
        }

        $definition = new Definition($class_name);
        $definition->addTag('almanac.destination', array('name' => $name));

        if (isset($options['params'])) {
            $definition->addMethodCall('setParameters', array($options['params']));
        }

        $this->container->setDefinition($name, $definition)->setScope('prototype');
    }

    protected function configureDestinations(array $configuration)
    {
        if (!isset($configuration['destinations']) || !is_array($configuration['destinations'])) {
            return;
        }

        foreach ($configuration['destinations'] as $name => $options) {
            $this->configureDestination($name, $options);
        }
    }

    protected function configureFilter($name, $class_name, array $options = array())
    {
        $alias = isset($options['alias']) ? $options['alias'] : $name;

        $this->container
            ->register($name, $class_name)
            ->addTag('almanac.filter', array('name' => $alias));
    }

    protected function configureFilters(array $configuration)
    {
        if (!isset($configuration['filters']) || !is_array($configuration['filters'])) {
            return;
        }

        foreach ($configuration['filters'] as $name => $class_name) {
            $this->configureFilter($name, $class_name);
        }
    }

    protected function configureRenderer($name, $class_name, array $options = array())
    {
        $alias = isset($options['alias']) ? $options['alias'] : $name;

        $this->container
            ->register($name, $class_name)
            ->addTag('almanac.renderer', array('name' => $alias));
    }

    protected function configureRenderers(array $configuration)
    {
        if (!isset($configuration['renderers']) || !is_array($configuration['renderers'])) {
            return;
        }

        foreach ($configuration['renderers'] as $name => $class_name) {
            $this->configureRenderer($name, $class_name);
        }
    }

    protected function configureSource($name, array $options = array())
    {
        $class_name = null;

        if (isset($options['type'])) {
            $class_name = $this->getClassNameForDefaultType('source', $options['type']);
        } elseif (isset($options['class'])) {
            $class_name = $options['class'];
        }

        if (!$class_name) {
            throw new \Exception(sprintf('Unable to configure source "%s"', $name));
        }

        $definition = new Definition($class_name);
        $definition->addTag('almanac.source', array('name' => $name));

        if (isset($options['params'])) {
            foreach ($options['params'] as $value) {
                $definition->addArgument($value);
            }
        }

        $this->container->setDefinition($name, $definition);
    }

    protected function configureSources(array $configuration)
    {
        if (!isset($configuration['sources']) || !is_array($configuration['sources'])) {
            return;
        }

        foreach ($configuration['sources'] as $name => $options) {
            $this->configureSource($name, $options);
        }
    }

    protected function configureStatistic($name, $class_name)
    {
        $this->container
            ->register($name, $class_name)
            ->addMethodCall('setName', array($name))
            ->addTag('almanac.statistic', array('name' => $name));
    }

    protected function configureStatistics(array $configuration)
    {
        if (!isset($configuration['statistics']) || !is_array($configuration['statistics'])) {
            return;
        }

        foreach ($configuration['statistics'] as $name => $class_name) {
            $this->configureStatistic($name, $class_name);
        }
    }

    public function configure(array $configuration)
    {
        $this->configureParameters($configuration);

        $this->configureDestinations($configuration);
        $this->configureFilters($configuration);
        $this->configureRenderers($configuration);
        $this->configureSources($configuration);
        $this->configureStatistics($configuration);

        $this->container->compile();
    }

    public static function getInstance(ContainerBuilder $container)
    {
        $class = get_called_class();
        $instance = new $class($container);
        $instance->initialiseDefaults();
        return $instance;
    }
}
