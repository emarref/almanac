<?php

namespace Emarref\Almanac\Destination;

use Emarref\Almanac\Formatter\FormatterInterface;
use Emarref\Almanac\Reader\StatisticReaderInterface;
use Emarref\Almanac\Renderer\RendererInterface;
use Emarref\Almanac\Statistic\StatisticInterface;

class FilesystemDestination extends AbstractDestination implements FilesystemDestinationInterface
{
    public function getFilename()
    {
        $output_dir = $this->getParameter('output_dir');

        if (!$output_dir) {
            throw new \Exception('Output directory is a required parameter.');
        }

        if (!is_dir($output_dir) && !@mkdir($output_dir, 0755, true)) {
            throw new \Exception(sprintf('Output directory "%s" could not be created.', $output_dir));
        }

        $path = realpath($output_dir);

        if (!is_writable($path)) {
            throw new \Exception(sprintf('Output directory "%s" is not writable.', $path));
        }

        if (!$this->renderer) {
            throw new \Exception('Cannot determine filename without renderer.');
        }

        $file_extension = constant(sprintf('%s::FILE_EXTENSION', get_class($this->renderer)));

        return sprintf(
            '%s%s%s.%s',
            $path,
            DIRECTORY_SEPARATOR,
            $this->statistic->getName(),
            $file_extension
        );
    }

    public function read()
    {
        $filename = $this->getFilename();

        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \Exception(sprintf('Destination file "%s" does not exist or is unreadable.', $filename));
        }

        if (false === ($content = file_get_contents($filename))) {
            throw new \Exception(sprintf('Could not read from destination file "%s"', $filename));
        }

        return $content;
    }

    public function write($content)
    {
        $filename = $this->getFilename();

        if (false === ($bytes_written = file_put_contents($filename, $content))) {
            throw new \Exception(sprintf('Could not write to destination file "%s"', $filename));
        }

        return $bytes_written;
    }

    /**
     * {@inheritdoc}
     */
    public function put($content)
    {
        if (!$this->statistic) {
            throw new \Exception('Statistic is required to put to destination.');
        }

        if (!$this->renderer) {
            throw new \Exception('Renderer is required to put to destination.');
        }

        $this->write($content);
    }
}
