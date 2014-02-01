<?php

namespace Emarref\Almanac\Builder;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Emarref\Almanac\Annotation\Statistic;
use Emarref\Almanac\Destination\DestinationInterface;
use Emarref\Almanac\Filter\Data\DataFilterInterface;
use Emarref\Almanac\Reader\ResultReaderInterface;
use Emarref\Almanac\Reader\StatisticReaderInterface;
use Emarref\Almanac\Renderer\RendererInterface;
use Emarref\Almanac\Source\SourceInterface;
use Emarref\Almanac\Statistic\StatisticInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * @param StatisticInterface $statistic
     * @return DestinationInterface
     */
    protected function getDestination(StatisticInterface $statistic)
    {
        return $this->getDestinationByName($this->getStatisticReader()->getDestinationName($statistic));
    }

    /**
     * @return StatisticReaderInterface
     */
    protected function getStatisticReader()
    {
        return $this->container->get('almanac.reader.statistic');
    }

    /**
     * @return ResultReaderInterface
     */
    protected function getResultReader()
    {
        return $this->container->get('almanac.reader.result');
    }

    /**
     * @param $name
     * @return DestinationInterface
     * @throws \Exception
     */
    protected function getDestinationByName($name)
    {
        $instance = $this->container->get('almanac.manager.destination')->findOneByName($name);

        if (!$instance) {
            throw new \Exception(sprintf('Destination "%s" could not be found.', $name));
        } elseif (!($instance instanceof DestinationInterface)) {
            throw new \Exception(sprintf('Destination "%s" must implement Emarref\Almanac\Destination\DestinationInterface.', $name));
        }

        return $instance;
    }

    protected function getRenderer(StatisticInterface $statistic)
    {
        /** @var AnnotationReader $annotation_reader */
        $annotation_reader = $this->container->get('almanac.reader.annotation');

        /** @var Statistic $statistic_annotation */
        $statistic_annotation = $annotation_reader->getClassAnnotation(new \ReflectionClass($statistic), 'Emarref\Almanac\Annotation\Statistic');

        return $this->getRendererByName($statistic_annotation->getRenderer());
    }

    /**
     * @param $name
     * @return RendererInterface
     * @throws \Exception
     */
    protected function getRendererByName($name)
    {
        $instance = $this->container->get('almanac.manager.renderer')->findOneByName($name);

        if (!$instance) {
            throw new \Exception(sprintf('Renderer "%s" could not be found.', $name));
        } elseif (!($instance instanceof RendererInterface)) {
            throw new \Exception(sprintf('Renderer "%s" must implement Emarref\Almanac\Renderer\RendererInterface.', $name));
        }

        return $instance;
    }

    /**
     * @param $name
     * @return SourceInterface
     * @throws \Exception
     */
    protected function getSourceByName($name)
    {
        $instance = $this->container->get('almanac.manager.source')->findOneByName($name);

        if (!$instance) {
            throw new \Exception(sprintf('Source "%s" could not be found.', $name));
        } elseif (!($instance instanceof SourceInterface)) {
            throw new \Exception(sprintf('Source "%s" must implement Emarref\Almanac\Source\SourceInterface.', $name));
        }

        return $instance;
    }

    protected function getSource(StatisticInterface $statistic)
    {
        /** @var AnnotationReader $annotation_reader */
        $annotation_reader = $this->container->get('almanac.reader.annotation');

        /** @var Statistic $statistic_annotation */
        $statistic_annotation = $annotation_reader->getClassAnnotation(new \ReflectionClass($statistic), 'Emarref\Almanac\Annotation\Statistic');

        return $this->getSourceByName($statistic_annotation->getSource());
    }

    /**
     * @param $name
     * @return DataFilterInterface
     * @throws \Exception
     */
    protected function getFilterByName($name)
    {
        $instance = $this->container->get('almanac.manager.filter')->findOneByName($name);

        if (!$instance) {
            throw new \Exception(sprintf('Filter "%s" could not be found.', $name));
        } elseif (!($instance instanceof DataFilterInterface)) {
            throw new \Exception(sprintf('Filter "%s" must implement Emarref\Almanac\Filter\Data\DataFilterInterface.', $name));
        }

        return $instance;
    }

    /**
     * Responsible for taking a statistic, and formatting it into an array ready for rendering.
     *
     * @see RendererInterface::format() for detail on expected return format
     * @param StatisticInterface $statistic
     * @return array
     */
    public function format(StatisticInterface $statistic)
    {
        // Take a statistic, return a standard array
        $formatted = array();

        $statistic_reader = $this->getStatisticReader();
        $result_reader    = $this->getResultReader();

        $statistic_source = $this->getSource($statistic);

        $formatted['heading']      = $statistic_reader->getHeading($statistic);
        $formatted['introduction'] = $statistic_reader->getIntroduction($statistic);
        $formatted['results']      = array();

        foreach ($statistic_reader->getResults($statistic) as $result_name) {
            $result_source = $this->getSource($statistic, $result_name);

            $source = $result_source ?: $statistic_source;

            $seed = $result_reader->getSeed($statistic, $result_name);

            $result = array();

            $data = $source->retrieve($seed);

            $filters = $result_reader->getFilters($statistic, $result_name);

            foreach ($filters as $filter_name => $params) {
                /** @var DataFilterInterface $filter */
                $filter = $this->getFilterByName($filter_name);
                $data = $filter->filter($data);
            }

            $result['heading']      = $result_reader->getHeading($statistic, $result_name);
            $result['introduction'] = $result_reader->getIntroduction($statistic, $result_name);
            $result['content']      = $data;

            $formatted['results'][] = $result;
        }

        return $formatted;
    }
}