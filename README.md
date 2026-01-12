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

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Create a line chart
$chart = new Chart(ChartType::Line);

// Add data
$sales = new DataSeries('Monthly Sales', [
    new DataPoint(1.0, 100.0),
    new DataPoint(2.0, 150.0),
    new DataPoint(3.0, 120.0),
    new DataPoint(4.0, 180.0),
], '#3498db');

// Configure and generate
$chart->setSize(800, 600)
      ->setFormat(ImageFormat::SVG)
      ->setBackgroundColor('#FFFFFF')
      ->addDataSeries($sales)
      ->generate('output/chart.svg');
```

## Current Implementation Status

### ✅ Implemented (MVP)

- **Foundation**: Exception hierarchy, enums, utilities
- **Data structures**: DataPoint, DataSeries with validation
- **Chart class**: Fluent interface API
- **SVG renderer**: Line chart rendering with multiple series
- **File generation**: Save charts to files
- **Quality gates**: PHPStan level 10, PSR-12, strict types

### 🚧 In Progress

- **PNG/WEBP rendering**: GD-based raster renderer
- **Bar charts**: Vertical and horizontal bars
- **Configuration**: Grid lines, axis scaling, labels, legend
- **Additional chart types**: Pie, Scatter, Radar

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

See the `examples/` directory for working examples:

- `basic-line-chart.php` - Simple line chart

Run examples:

```bash
php examples/basic-line-chart.php
```

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
