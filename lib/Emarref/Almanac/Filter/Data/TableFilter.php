<?php

namespace Emarref\Almanac\Filter\Data;

use Emarref\Almanac\Util\DataFormatUtil;

class TableFilter implements DataFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter(array $data)
    {
        return DataFormatUtil::tableize($data);
    }
}