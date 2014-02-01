<?php

namespace Emarref\Almanac\Filter\Data;

use Emarref\Almanac\Util\DataFormatUtil;

class AverageFilter implements DataFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter(array $data)
    {
        $data = array_map(function ($value) {
            $count = array_values($value);
            return $count[0];
        }, $data);

        return DataFormatUtil::tableize(array(array(
            'Min'           => number_format(min($data), 1),
            'Max'           => number_format(max($data), 1),
            'Mean'          => number_format(array_sum($data)/count($data), 1),
            'Median'        => number_format($data[floor(count($data) / 2)], 1),
            // 'Variance'      => number_format(Stats::variance($data), 1),
            // 'Standard Deviation' => number_format(Stats::stddev($data), 1),
            'Sample Size'   => number_format(array_sum($data), 1),
        )));
    }
}
