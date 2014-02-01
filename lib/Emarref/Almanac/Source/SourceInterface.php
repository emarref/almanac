<?php

namespace Emarref\Almanac\Source;

interface SourceInterface
{
    /**
     * @param string $seed
     * @return array
     */
    public function retrieve($seed);
}