<?php

namespace Emarref\Almanac\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Filter
{
    /**
     * @var array
     */
    private $filters = array();

    public function __construct(array $options)
    {
        $value = $options['value'];

        if (is_string($value)) {
            // @Filter("filter_type")
            $this->filters[$value] = array();
        } elseif (is_array($value) && is_string($value[0]) && is_array($value[1])) {
            // @Filter("filter_type" = { "param" = "value" })
            $this->filters[$value[0]] = $value[1];
        } elseif (is_array($value) && is_array($value[0])) {
            // @Filter({ "filter_type_one", "filter_type_two" })
            foreach ($value[0] as $name => $params) {
                $this->filters[$name] = array();
            }
        } else {
            throw new \InvalidArgumentException(sprintf('Could not understand filter with value %s.', json_encode($options)));
        }
    }

    public function getFilters()
    {
        return $this->filters;
    }
}