<?php

namespace Emarref\Almanac\Destination;

use Emarref\Almanac\Renderer\RendererInterface;
use Emarref\Almanac\Statistic\StatisticInterface;

abstract class AbstractDestination implements DestinationInterface
{
    /**
     * @var StatisticInterface
     */
    protected $statistic;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @param StatisticInterface $statistic
     */
    public function setStatistic(StatisticInterface $statistic)
    {
        $this->statistic = $statistic;
    }

    /**
     * @param RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function setParameters(array $params)
    {
        foreach ($params as $name => $value) {
            $this->setParameter($name, $value);
        }
    }

    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function getParameter($name, $default = null)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name]: $default;
    }
}