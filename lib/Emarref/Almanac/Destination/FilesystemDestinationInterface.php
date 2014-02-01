<?php

namespace Emarref\Almanac\Destination;

interface FilesystemDestinationInterface
{
    public function getFilename();

    public function read();

    public function write($content);
}