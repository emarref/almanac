<?php

namespace Emarref\Almanac\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Emarref\Almanac\Statistic\StatisticInterface;

interface ResultReaderInterface
{
    public function __construct(AnnotationReader $reader);

    /**
     * @param StatisticInterface $statistic
     * @param string $result_name
     * @return string
     */
    public function getHeading(StatisticInterface $statistic, $result_name);

    /**
     * @param StatisticInterface $statistic
     * @param string $result_name
     * @return string
     */
    public function getIntroduction(StatisticInterface $statistic, $result_name);

    /**
     * @param StatisticInterface $statistic
     * @param string $result_name
     * @return string
     */
    public function getSeed(StatisticInterface $statistic, $result_name);

    /**
     * @param StatisticInterface $statistic
     * @param string $result_name
     * @return array<Emarref\Almanac\Filter\Data\FilterInterface>
     */
    public function getFilters(StatisticInterface $statistic, $result_name);
}