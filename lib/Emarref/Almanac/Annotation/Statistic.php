<?php

namespace Emarref\Almanac\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Statistic
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $renderer;

    /**
     * @var string
     */
    private $destination;

    /**
     * @param array $options
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options)
    {
        $this->setSource($options['source']);
        $this->setRenderer($options['renderer']);
        $this->setDestination($options['destination']);

        unset(
            $options['source'],
            $options['renderer'],
            $options['destination']
        );

        if (count($options)) {
            $unknown_options = array_keys($options);
            throw new \InvalidArgumentException(sprintf('Unknown Statistic options: %s', implode(', ', $unknown_options)));
        }
    }

    /**
     * @param string $destination
     * @return Statistic
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $renderer
     * @return Statistic
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @return string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param string $source
     * @return Statistic
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}