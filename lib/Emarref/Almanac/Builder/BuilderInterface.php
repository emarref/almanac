<?php

namespace Emarref\Almanac\Builder;

use Emarref\Almanac\Statistic\StatisticInterface;

interface BuilderInterface
{
    /**
     * @param StatisticInterface $statistic
     */
    public function build(StatisticInterface $statistic);
}