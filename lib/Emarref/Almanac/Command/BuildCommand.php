<?php

namespace Emarref\Almanac\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Emarref\Almanac\AlmanacKernel;
use Emarref\Almanac\Statistic\StatisticInterface;

class BuildCommand extends AlmanacCommand
{
    protected function configure()
    {
        $this
            ->setName('almanac:build')
            ->setDescription('Builds almanac according to the configuration.')
            ->addArgument('statistic', InputArgument::OPTIONAL, 'The name of a configured statistic to build', 'all')
            ->addOption('config', 'c', InputArgument::OPTIONAL, 'A yaml file from whic to read Almanac configuration', 'Almanac.yml')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $statistic_name = $input->getArgument('statistic');
        $config_file    = $input->getOption('config');

        if (!file_exists($config_file) || !is_readable($config_file)) {
            $this->fail(sprintf('Configuration "%s" does not exist or is unreadable.', $config_file), $output);
        }

        AlmanacKernel::getInstance($this->getContainer())
            ->configure(Yaml::parse($config_file));

        if ($statistic_name && 'all' !== $statistic_name) {
            /** @var StatisticInterface $statistic */
            $statistic = $this->getContainer()->get($statistic_name);
            $this->buildStatistic($statistic, $output);
        } else {
            $this->buildAllStatistics($output);
        }
    }

    protected function buildAllStatistics(OutputInterface $output)
    {
        $start_time = microtime(true);

        $statistic_manager = $this->getContainer()->get('almanac.manager.statistic');

        foreach ($statistic_manager as $statistic) {
            $this->buildStatistic($statistic, $output);
        }

        $duration = bcsub(microtime(true), $start_time, 2);

        $output->writeln(sprintf('All statistics built in <info>%s</info> seconds.', $duration));
    }

    protected function buildStatistic(StatisticInterface $statistic, OutputInterface $output)
    {
        $start_time = microtime(true);

        $output->writeln(sprintf('Building statistic "<info>%s</info>".', $statistic->getName()));

        $statistic_builder = $this->getContainer()->get('almanac.builder.statistic');
        $statistic_builder->build($statistic);

        $duration = bcsub(microtime(true), $start_time, 2);
        $output->writeln(sprintf('Finished in <info>%s</info> seconds.', $duration));
    }

    protected function fail($message, $output)
    {
        $output->writeln(sprintf('<error>%s</error>', $message));
        exit(1);
    }
}