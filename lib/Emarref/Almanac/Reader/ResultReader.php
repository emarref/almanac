<?php

namespace Emarref\Almanac\Reader;

use Emarref\Almanac\Annotation\Filter;
use Emarref\Almanac\Statistic\StatisticInterface;

class ResultReader extends AbstractClassReader implements ResultReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getHeading(StatisticInterface $statistic, $result_name)
    {
        return $this->getDocBlock($statistic, $result_name)->getShortDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function getIntroduction(StatisticInterface $statistic, $result_name)
    {
        return $this->getDocBlock($statistic, $result_name)->getLongDescription()->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function getSeed(StatisticInterface $statistic, $result_name)
    {
        return $statistic->$result_name();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(StatisticInterface $statistic, $result_name)
    {
        $reflection_method = new \ReflectionMethod($statistic, $result_name);

        /** @var Filter $filter_annotation */
        $filter_annotation = $this->reader->getMethodAnnotation($reflection_method, 'Emarref\Almanac\Annotation\Filter');

        if ($filter_annotation) {
            $filters = $filter_annotation->getFilters();
        } else {
            $filters = array('table' => array());
        }

        return $filters;
    }
}