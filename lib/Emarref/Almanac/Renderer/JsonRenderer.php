<?php

namespace Emarref\Almanac\Renderer;

class JsonRenderer implements RendererInterface
{
    const FILE_EXTENSION = 'json';
    const MIME_TYPE      = 'application/json';

    /**
     * {@inheritdoc}
     */
    public function render(array $content)
    {
        return json_encode($content);
    }
}