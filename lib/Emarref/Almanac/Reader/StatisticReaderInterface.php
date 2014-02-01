<?php

namespace Emarref\Almanac\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Emarref\Almanac\Statistic\StatisticInterface;

interface StatisticReaderInterface
{
    public function __construct(AnnotationReader $reader);

    /**
     * @param StatisticInterface $statistic
     * @return string
     */
    public function getHeading(StatisticInterface $statistic);

    /**
     * @param StatisticInterface $statistic
     * @return string
     */
    public function getIntroduction(StatisticInterface $statistic);

    /**
     * @param StatisticInterface $statistic
     * @return array<ResultReaderInterface>
     */
    public function getResults(StatisticInterface $statistic);
}