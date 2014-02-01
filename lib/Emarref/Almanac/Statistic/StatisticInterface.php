<?php

namespace Emarref\Almanac\Statistic;

interface StatisticInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return StatisticInterface
     */
    public function setName($name);
}