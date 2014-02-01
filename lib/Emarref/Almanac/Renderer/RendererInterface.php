<?php

namespace Emarref\Almanac\Renderer;

use Emarref\Almanac\Statistic\StatisticInterface;

interface RendererInterface
{
    /**
     * Responsible for taking an array representing a formatted statistic in a common array format, and returning the
     * content ready to persist to a destination. Expected array format:
     *
     * array(
     *     'heading' => '...',
     *     'introduction' => '...',
     *     'results' => array(
     *         array(
     *             'heading' => '...',
     *             'introduction' => '...',
     *             'content' => '...'
     *         ),
     *         [...]
     *     )
     * )
     *
     * @param array $content
     * @return string
     */
    public function render(array $content);
}
