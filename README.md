# Almanac

Almanac is a framework for generating statistics repeatedly and consistently, and producing documentation and visualisations for those statistics.

To generate statistics, execute the ```bin/almanac almanac:build [statistic_name]``` tool. See ```./bin/almanac help almanac:build``` for more options. This will read the configuration file (Almanac.yml by default) and generate one or more statistics described within it.

## Configuration

See [examples/Almanac.yml](examples/Almanac.yml) for an example configuration.

### Destinations

Given rendered content, destinations are responsible for putting it somewhere. Currently available destination types are:

- filesystem

You can pass a ```class``` instead of a ```type``` to use a custom destination, as long as that class implements the ```Emarref\Almanac\Destination\DestinationInterface``` interface.

### Filters

The output from each result on a statistic can be passed through multiple filters before being sent to the renderer. Currently available filters are:

- table
- average

The table filter takes a standard key => value array and turns it into a tabular format by placing the array keys as the first row (like a header), then the array values as subsequent rows.

The average filter takes a result set of several rows of single integers, and calculates common statistical analytical figures for that result set, then returns it in table format.

Filter classes must implement the ```Emarref\Almanac\Filter\Data\DataFilterInterface``` interface.

### Renderers

Renderers are given a standardised array representation of the statistic content, and are expected to return that content in a string format ready for the configured destination.

Currently available renderers are:

- markdown
- json
- html

Renderer classes must implement the ```Emarref\Almanac\Renderer\RendererInterface``` interface.

### Sources

Sources are where data is currently stored and can be interrogated against. Sources are responsible for taking the seed from the statistic, and returning the data it should generate.

Currently available source types are:

- mysql

You can pass a ```class``` instead of a ```type``` to use a custom source, as long as that class implements the ```Emarref\Almanac\Source\SourceInterface``` interface.

### Statistics

A statistic is any self-documenting class that implements the ```Emarref\Almanac\Statistic\StatisticInterface``` interface. Your class may implement the ```Emarref\Almanac\Statistic\AbstractStatistic``` class which provides some simple helper functions.

The first line of the class docblock is used as a heading. The rest of the docblock that is not annotated is used as the description. This class should be annotated with the ```Emarref\Almanac\Annotation\Statistic``` annotation. Each method in this class that is annotated with the ```Emarref\Almanac\Annotation\Result``` annotation will be compiled as a result by your renderer and destination. Each Result on your Statistic should return something that the configured source can use to retrieve data. If this method is annotated with the ```Emarref\Almanac\Annotation\Filter``` annotation, the data from the source will be passed through one or more named filters as annotated.

See [examples/UserStatistic.php](examples/UserStatistic.php) for an example statistic class.

### Commands

Almanac commands use the [Symfony Console](https://github.com/symfony/Console) component. An Almanac command is any Symfony command that also implements the ```Emarref\Almanac\Command\CommandInterface``` command.

## Todo

- Implement png graphing formatter
- Implement HTML/JS formatter + graphs
- Don't hardcode Almanac.yml into command line tool
- Test suite
- Comprehensive documentation