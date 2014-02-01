<?php

namespace Emarref\Almanac\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use phpDocumentor\Reflection\DocBlock;
use Emarref\Almanac\Statistic\StatisticInterface;

abstract class AbstractClassReader
{
    /**
     * @var AnnotationReader
     */
    protected $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Returns a reflection instance of the statistic.
     *
     * @param StatisticInterface $statistic
     * @return \ReflectionClass
     */
    public function getReflection(StatisticInterface $statistic)
    {
        return new \ReflectionClass($statistic);
    }

    /**
     * Returns a parsed object representing the statistic doc block.
     *
     * @param StatisticInterface $statistic
     * @param string $method_name
     * @return DocBlock
     */
    protected function getDocBlock(StatisticInterface $statistic, $method_name = null)
    {
        $reflection = $this->getReflection($statistic);

        if (null === $method_name) {
            $doc_comment = $reflection->getDocComment();
        } else {
            $doc_comment = $reflection->getMethod($method_name)->getDocComment();
        }

        return new DocBlock($doc_comment);
    }

}