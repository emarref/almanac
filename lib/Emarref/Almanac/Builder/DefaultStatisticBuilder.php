<?php

namespace Emarref\Almanac\Builder;

use Emarref\Almanac\Statistic\StatisticInterface;

class DefaultStatisticBuilder extends AbstractBuilder
{
    public function build(StatisticInterface $statistic)
    {
        $renderer = $this->getRenderer($statistic);

        $formatted_content = $this->format($statistic);

        $rendered_content  = $renderer->render($formatted_content);

        $destination = $this->getDestination($statistic);

        $destination->setStatistic($statistic);
        $destination->setRenderer($renderer);

        $destination->put($rendered_content);
    }
}