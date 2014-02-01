<?php

namespace Emarref\Almanac\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractManager implements \Iterator
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var array
     */
    private $managedItems = array();

    /**
     * @var int
     */
    private $position = 0;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $name
     * @param array $attributes
     */
    public function manageItem($name, $attributes)
    {
        $this->managedItems[] = array('name' => $name, 'attributes' => $attributes);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        $name = $this->managedItems[$this->position]['name'];

        return $this->container->get($name);
    }

    public function key()
    {
        return $this->managedItems[$this->position]['name'];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->managedItems[$this->position]);
    }

    public function findOneByName($name)
    {
        foreach ($this->managedItems as $item) {
            $item_name       = $item['name'];
            $item_attributes = $item['attributes'];

            if (!empty($item_attributes['name']) && $name === $item_attributes['name']) {
                return $this->container->get($item_name);
            }
        }
    }
}