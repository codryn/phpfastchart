# PHPFastChart

[![PHP Version](https://img.shields.io/badge/PHP-8.1--8.5-blue.svg)](https://www.php.net/)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg)](https://phpstan.org/)
[![CI](https://github.com/codryn/phpfastchart/workflows/CI/badge.svg)](https://github.com/codryn/phpfastchart/actions)
[![Latest Stable Version](https://poser.pugx.org/codryn/phpfastchart/v/stable)](https://packagist.org/packages/codryn/phpfastchart)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

> PHP library to generate simple charts fast.

**PHPFastChart** is a fast and simple charting library for PHP 8.1+, ideal for generating charts in web applications and reports.

## Features

- 🚀 **Zero dependencies** - Only uses PHP's built-in GD extension (SVG is pure PHP)
- 📊 **Multiple chart types** - Line, Bar, Pie, Scatter, and Radar charts
- 🎨 **Customizable** - Colors, dimensions, and styling options
- 📁 **Multiple formats** - PNG, WEBP, and SVG output
- ✅ **PHP 8.1+** - Modern PHP with strict types and readonly properties
- 🧪 **Well tested** - PHPUnit tests with high coverage
- 📝 **Documented** - Complete PHPDoc for all public APIs

## Requirements

- PHP 8.1 or higher
- GD extension (for PNG/WEBP output)

## Installation

```bash
composer require codryn/phpfastchart
```

## Quick Start

### Basic Line Chart

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Create a line chart
$chart = new Chart(800, 600, ChartType::Line, ImageFormat::PNG);

// Add data series
$sales = new DataSeries(
    'Monthly Sales',
    [
        new DataPoint(0, 100, 'Jan'),
        new DataPoint(1, 150, 'Feb'),
        new DataPoint(2, 120, 'Mar'),
        new DataPoint(3, 180, 'Apr'),
    ],
    '#3498db'
);

// Configure and generate
$chart
    ->addSeries($sales)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Sales Report')
    ->setAxisLabel('x', 'Month')
    ->setAxisLabel('y', 'Sales ($)')
    ->generate('output/chart.png');
```

### Multi-Series Bar Chart with Legend

```php
$chart = new Chart(1000, 600, ChartType::Bar, ImageFormat::SVG);

$series1 = new DataSeries('Product A', [
    new DataPoint(0, 50, 'Q1'),
    new DataPoint(1, 75, 'Q2'),
    new DataPoint(2, 60, 'Q3'),
    new DataPoint(3, 90, 'Q4'),
], '#0066CC');

$series2 = new DataSeries('Product B', [
    new DataPoint(0, 40, 'Q1'),
    new DataPoint(1, 65, 'Q2'),
    new DataPoint(2, 80, 'Q3'),
    new DataPoint(3, 95, 'Q4'),
], '#FF6600');

$chart
    ->addSeries($series1)
    ->addSeries($series2)
    ->setTitle('Quarterly Revenue')
    ->setAxisRange('y', 0, 100)
    ->enableGrid(false, true)
    ->enableLegend(\Codryn\PHPFastChart\Configuration\LegendPosition::TopRight)
    ->generate('output/bar-chart.svg');
```

### Pie Chart

```php
$chart = new Chart(800, 800, ChartType::Pie, ImageFormat::PNG);

$marketShare = new DataSeries('Market Share', [
    new DataPoint(0, 35, 'Product A'),
    new DataPoint(1, 25, 'Product B'),
    new DataPoint(2, 20, 'Product C'),
    new DataPoint(3, 15, 'Product D'),
    new DataPoint(4, 5, 'Others'),
], '#0066CC');

$chart
    ->addSeries($marketShare)
    ->setTitle('Market Share Distribution')
    ->enableLegend(\Codryn\PHPFastChart\Configuration\LegendPosition::Right)
    ->generate('output/pie-chart.png');
```

## Feature Overview

### Chart Types

- **Line Chart** - Multi-series line graphs with customizable styles
- **Bar Chart** - Vertical bar charts with grouped series support
- **Scatter Plot** - X/Y coordinate scatter plots for data distribution
- **Pie Chart** - Circular percentage charts (single series)
- **Radar Chart** - Multi-dimensional comparison charts (spider/web charts)

### Output Formats

- **PNG** - High-quality raster images (requires GD extension)
- **WEBP** - Modern compressed raster format (requires GD extension)
- **SVG** - Scalable vector graphics (pure PHP, no dependencies)

### Customization Features

- ✅ **Colors** - Customize background, axes, grid, series colors
- ✅ **Grid Lines** - Horizontal/vertical grid with customizable spacing and style
- ✅ **Axis Scaling** - Manual or automatic axis ranges with clip modes
- ✅ **Labels** - Chart titles, axis labels, data point labels
- ✅ **Legend** - Configurable position and styling for multi-series charts
- ✅ **Dimensions** - Any size from small thumbnails to 4000×4000 large images

### Quality Assurance

- ✅ **PHPStan Level 10** - Strictest static analysis
- ✅ **PSR-12** - PHP coding standards compliance
- ✅ **Strict Types** - Type safety in all files
- ✅ **TDD** - Comprehensive test coverage
- ✅ **PHP 8.1-8.5** - Multi-version compatibility

## Development

### Running Tests

```bash
# All tests
composer test

# Unit tests only
vendor/bin/phpunit tests/Unit

# Integration tests
vendor/bin/phpunit tests/Integration
```

### Code Quality

```bash
# PHPStan (level 10)
composer analyse

# PHP-CS-Fixer (PSR-12)
composer cs-fix

# All checks
composer ci
```

## Examples

See the `examples/` directory for comprehensive working examples:

### Basic Examples
- `basic-line-chart.php` - Simple line chart
- `bar-chart.php` - Bar chart example
- `scatter-chart-example.php` - Scatter plot
- `pie-chart.php` - Pie chart with percentages
- `radar-chart.php` - Radar/spider chart

### Feature Examples
- `format-comparison.php` - Generate charts in PNG, WEBP, and SVG
- `custom-colors.php` - Color customization
- `grid-lines.php` - Grid configuration
- `labels.php` - Axis labels and titles
- `legend-example.php` - Legend positioning and styling
- `axis-scaling.php` - Manual and automatic axis ranges

### Advanced Examples
- `advanced-styling-example.php` - All features combined
- `svg-export-example.php` - SVG-specific features and advantages

Run examples:

```bash
# Run all examples
composer run-examples

# Or run individual examples
php examples/basic-line-chart.php
php examples/advanced-styling-example.php
```

**Note**: PNG and WEBP formats require the GD extension. SVG works with pure PHP.

## Documentation

- **[Feature Specification](docs/CHART_GENERATOR_SPEC.md)** - Complete specification for chart generator features
- **[Constitution](.specify/memory/constitution.md)** - Project quality standards and development principles
- **[Examples](examples/)** - Chart specific examples

## Development

See [CONTRIBUTING.md](CONTRIBUTING.md) for development guidelines.

## Architecture

TODO: Add architecture diagram and explanation here.

## Performance

TODO: Add performance benchmarks and comparisons here.

## Quality Standards

- ✅ **PHPStan Level 10**: Strictest static analysis level from PHPSTan 2.1
- ✅ **PSR-12**: PHP coding standards compliance
- ✅ **Strict Types**: `declare(strict_types=1)` in all files
- ✅ **TDD**: Test-driven development methodology
- ✅ **Type Hints**: Full type declarations on all methods
- ✅ **PHPDoc**: Complete documentation blocks

## Contributing

Contributions are welcome! See [CONTRIBUTING.md](CONTRIBUTING.md) for:
- Development workflow
- Coding standards
- Testing requirements
- Pull request process

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and migration guides.

## Support

- 📖 [Documentation](docs/)
- 🐛 [Issue Tracker](https://github.com/codryn/phpfastchart/issues)
- 💬 [Discussions](https://github.com/codryn/phpfastchart/discussions)
- 📧 [Email](mailto:info@codryn.com)

## Credits

Created and maintained by Marco for [Codryn](https://codryn.com).

Special thanks to:
- The PHP community
- PHPUnit, PHPStan, and PHP-CS-Fixer maintainers

---

Built for the tabletop RPG community 🎲
