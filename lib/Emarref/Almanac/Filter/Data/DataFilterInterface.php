<?php

namespace Emarref\Almanac\Filter\Data;

interface DataFilterInterface
{
    /**
     * Takes raw data from a source and filters or manipulates it before formatting.
     *
     * @param array $data
     * @return array
     */
    public function filter(array $data);
}