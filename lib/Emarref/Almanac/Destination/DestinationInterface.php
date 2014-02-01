<?php

namespace Emarref\Almanac\Destination;

use Emarref\Almanac\Renderer\RendererInterface;
use Emarref\Almanac\Statistic\StatisticInterface;

interface DestinationInterface
{
    /**
     * @param string $content
     */
    public function put($content);

    /**
     * @param array $params
     * @return mixed
     */
    public function setParameters(array $params);

    /**
     * @param StatisticInterface $statistic
     */
    public function setStatistic(StatisticInterface $statistic);

    /**
     * @param RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer);
}