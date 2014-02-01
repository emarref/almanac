<?php

namespace Emarref\Almanac\Statistic;

use PHPStats\Stats;
use Emarref\Almanac\Util\DataFormatUtil;

class AverageStatistic extends AbstractStatistic
{
    /**
     * {@inheritdoc}
     */
    public function filterData(array $data)
    {
        $data = array_map(function ($value) {
            $count = array_values($value);
            return $count[0];
        }, $data);

        return DataFormatUtil::tableize(array(array(
            'Min'           => number_format(min($data), 1),
            'Max'           => number_format(max($data), 1),
            'Mean'          => number_format(Stats::average($data), 1),
            'Median'        => number_format($data[floor(count($data)/2)], 1),
            'Variance'      => number_format(Stats::variance($data), 1),
            'Standard Deviation' => number_format(Stats::stddev($data), 1),
            'Sample Size'   => number_format(Stats::sum($data), 1),
        )));
    }
}