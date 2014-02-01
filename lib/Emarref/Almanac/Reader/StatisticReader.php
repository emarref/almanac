<?php

namespace Emarref\Almanac\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use phpDocumentor\Reflection\DocBlock;
use Emarref\Almanac\Annotation\Statistic;
use Emarref\Almanac\Statistic\StatisticInterface;

class StatisticReader extends AbstractClassReader implements StatisticReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getHeading(StatisticInterface $statistic)
    {
        return $this->getDocBlock($statistic)->getShortDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function getIntroduction(StatisticInterface $statistic)
    {
        return $this->getDocBlock($statistic)->getLongDescription()->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function getResults(StatisticInterface $statistic)
    {
        $reflection = $this->getReflection($statistic);
        $results = array();

        foreach ($reflection->getMethods() as $reflection_method) {
            if ($this->reader->getMethodAnnotation($reflection_method, 'Emarref\Almanac\Annotation\Result')) {
                $results[] = $reflection_method->getName();
            }
        }

        return $results;
    }

    /**
     * @param $statistic
     * @return Statistic
     */
    protected function getStatisticAnnotation(StatisticInterface $statistic)
    {
        return $this->reader->getClassAnnotation($this->getReflection($statistic), 'Emarref\Almanac\Annotation\Statistic');
    }

    /**
     * @param $statistic
     * @return string
     */
    public function getFormatterName(StatisticInterface $statistic)
    {
        return $this->getStatisticAnnotation($statistic)->getFormatter();
    }

    /**
     * @param $statistic
     * @return string
     */
    public function getSourceName(StatisticInterface $statistic)
    {
        return $this->getStatisticAnnotation($statistic)->getSource();
    }

    /**
     * @param $statistic
     * @return string
     */
    public function getDestinationName(StatisticInterface $statistic)
    {
        return $this->getStatisticAnnotation($statistic)->getDestination();
    }
}