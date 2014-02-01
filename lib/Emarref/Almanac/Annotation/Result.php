<?php

namespace Emarref\Almanac\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Result
{
    /**
     * @var string
     */
    public $filter;
}