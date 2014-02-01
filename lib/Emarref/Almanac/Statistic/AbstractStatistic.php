<?php

namespace Emarref\Almanac\Statistic;

abstract class AbstractStatistic implements StatisticInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     * @return StatisticInterface
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}