# Chart API Contract

**Feature**: 2D Chart Generator
**Version**: 1.0.0
**Date**: 2026-01-12

## Public API Surface

This document defines the complete public API that users interact with.

## Core Chart API

### Basic Usage Pattern

```php
<?php declare(strict_types=1);

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Data\DataPoint;

// Create chart
$chart = new Chart(ChartType::Line);

// Configure
$chart->setSize(800, 600)
      ->setFormat(ImageFormat::PNG)
      ->setBackgroundColor('#FFFFFF');

// Add data
$series = new DataSeries('Sales', [
    new DataPoint(1, 100),
    new DataPoint(2, 150),
    new DataPoint(3, 120),
]);
$chart->addDataSeries($series);

// Generate
$chart->generate('/path/to/output.png');
```

---

## Chart Class

### Constructor

```php
public function __construct(ChartType $type)
```

**Parameters**:
- `$type`: Chart type enum value

**Example**:
```php
$chart = new Chart(ChartType::Bar);
```

---

### Dimension Configuration

```php
public function setSize(int $width, int $height): self
```

**Parameters**:
- `$width`: Image width in pixels (50-4000)
- `$height`: Image height in pixels (50-4000)

**Returns**: `$this` for chaining

**Throws**:
- `InvalidArgumentException` if dimensions < 50 or > 4000

**Example**:
```php
$chart->setSize(1200, 800);
```

---

### Format Configuration

```php
public function setFormat(ImageFormat $format): self
```

**Parameters**:
- `$format`: Image format enum (PNG, WEBP, SVG)

**Returns**: `$this` for chaining

**Example**:
```php
$chart->setFormat(ImageFormat::SVG);
```

---

### Color Configuration

```php
public function setBackgroundColor(string $color): self
```

**Parameters**:
- `$color`: Hex (#RRGGBB, #RRGGBBAA) or named color

**Returns**: `$this` for chaining

**Throws**:
- `InvalidArgumentException` if color format invalid

**Examples**:
```php
$chart->setBackgroundColor('#FFFFFF');
$chart->setBackgroundColor('#FFFFFF00'); // Transparent
$chart->setBackgroundColor('white');
```

---

```php
public function setAxisColor(string $color): self
```

**Parameters**:
- `$color`: Hex or named color

**Returns**: `$this` for chaining

---

### Data Management

```php
public function addDataSeries(DataSeries $series): self
```

**Parameters**:
- `$series`: DataSeries instance

**Returns**: `$this` for chaining

**Example**:
```php
$series = new DataSeries('Q1 Revenue', [
    new DataPoint('Jan', 1000),
    new DataPoint('Feb', 1200),
    new DataPoint('Mar', 1100),
]);
$chart->addDataSeries($series);
```

---

### Axis Configuration

```php
public function setAxisRange(
    string $axis,
    ?float $min = null,
    ?float $max = null
): self
```

**Parameters**:
- `$axis`: 'x' or 'y'
- `$min`: Minimum value (null for auto)
- `$max`: Maximum value (null for auto)

**Returns**: `$this` for chaining

**Throws**:
- `InvalidArgumentException` if axis not 'x' or 'y'
- `InvalidArgumentException` if min >= max

**Example**:
```php
$chart->setAxisRange('y', 0, 100); // Fixed Y range
$chart->setAxisRange('x', null, null); // Auto X range
```

---

```php
public function setAxisLabel(string $axis, string $label): self
```

**Parameters**:
- `$axis`: 'x' or 'y'
- `$label`: Label text

**Returns**: `$this` for chaining

**Example**:
```php
$chart->setAxisLabel('x', 'Month')
      ->setAxisLabel('y', 'Revenue ($)');
```

---

```php
public function setAxisClipMode(string $axis, AxisClipMode $mode): self
```

**Parameters**:
- `$axis`: 'x' or 'y'
- `$mode`: AxisClipMode enum (Throw or Clip)

**Returns**: `$this` for chaining

**Example**:
```php
use Codryn\PHPFastChart\Configuration\AxisClipMode;

$chart->setAxisClipMode('y', AxisClipMode::Clip); // Silently clip
```

---

### Grid Configuration

```php
public function enableGrid(
    bool $horizontal = true,
    bool $vertical = true,
    ?float $horizontalSpacing = null,
    ?float $verticalSpacing = null
): self
```

**Parameters**:
- `$horizontal`: Enable horizontal grid lines
- `$vertical`: Enable vertical grid lines
- `$horizontalSpacing`: Spacing in data units (null for auto)
- `$verticalSpacing`: Spacing in data units (null for auto)

**Returns**: `$this` for chaining

**Examples**:
```php
$chart->enableGrid(); // Both directions, auto spacing
$chart->enableGrid(true, false); // Horizontal only
$chart->enableGrid(true, true, 10.0, 5.0); // Custom spacing
```

---

```php
public function setGridStyle(string $color, int $lineWidth = 1): self
```

**Parameters**:
- `$color`: Grid line color
- `$lineWidth`: Line width in pixels

**Returns**: `$this` for chaining

**Example**:
```php
$chart->setGridStyle('#DDDDDD', 1);
```

---

### Legend Configuration

```php
public function enableLegend(
    LegendPosition $position = LegendPosition::TopRight
): self
```

**Parameters**:
- `$position`: Legend position enum

**Returns**: `$this` for chaining

**Example**:
```php
use Codryn\PHPFastChart\Configuration\LegendPosition;

$chart->enableLegend(LegendPosition::BottomRight);
```

---

```php
public function disableLegend(): self
```

**Returns**: `$this` for chaining

---

### Generation

```php
public function generate(string $outputPath): void
```

**Parameters**:
- `$outputPath`: File path for output image

**Throws**:
- `InvalidArgumentException` if no data series added
- `InvalidArgumentException` if empty dataset
- `InvalidConfigurationException` if conflicting settings
- `RenderException` if rendering fails
- `RenderException` if cannot write to path

**Example**:
```php
$chart->generate('/var/www/html/charts/sales.png');
```

---

```php
public function render(): string
```

**Returns**: Image data as binary string

**Throws**: Same as `generate()`

**Example**:
```php
$imageData = $chart->render();
header('Content-Type: image/png');
echo $imageData;
```

---

## DataSeries Class

### Constructor

```php
public function __construct(
    string $name,
    array $points,
    string $lineColor = '#0066CC',
    ?string $fillColor = null,
    bool $visible = true
)
```

**Parameters**:
- `$name`: Series name (non-empty)
- `$points`: Array of DataPoint objects
- `$lineColor`: Line/bar color (default blue)
- `$fillColor`: Fill color for areas (null = no fill)
- `$visible`: Whether series is rendered

**Throws**:
- `InvalidArgumentException` if name empty
- `InvalidArgumentException` if no points provided

**Example**:
```php
$series = new DataSeries(
    name: 'Revenue',
    points: [$point1, $point2, $point3],
    lineColor: '#FF6600',
    fillColor: '#FF660033' // Transparent orange
);
```

---

## DataPoint Class

### Constructor

```php
public function __construct(
    int|float|string $x,
    int|float|null $y = null,
    ?string $label = null
)
```

**Parameters**:
- `$x`: X-coordinate or category name
- `$y`: Y-coordinate value (null for pie chart labels)
- `$label`: Optional label for this point

**Examples**:
```php
// Numeric coordinates
new DataPoint(1, 100);
new DataPoint(1.5, 150.75);

// Category and value
new DataPoint('January', 1000);

// With label
new DataPoint(10, 50, 'Peak');

// Pie chart (category and value)
new DataPoint('Apples', 30);
```

---

## Enums

### ChartType

```php
enum ChartType: string
{
    case Bar = 'bar';
    case Line = 'line';
    case Pie = 'pie';
    case Scatter = 'scatter';
    case Radar = 'radar';
}
```

---

### ImageFormat

```php
enum ImageFormat: string
{
    case PNG = 'png';
    case WEBP = 'webp';
    case SVG = 'svg';
}
```

---

### AxisClipMode

```php
enum AxisClipMode: string
{
    case Throw = 'throw';  // Throw exception on out-of-range
    case Clip = 'clip';    // Silently clip data
}
```

---

### LegendPosition

```php
enum LegendPosition: string
{
    case TopLeft = 'top-left';
    case TopRight = 'top-right';
    case BottomLeft = 'bottom-left';
    case BottomRight = 'bottom-right';
    case Right = 'right';
}
```

---

## Complete Example: Multi-Series Line Chart

```php
<?php declare(strict_types=1);

require 'vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Configuration\LegendPosition;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Data\DataPoint;

// Create data
$revenue = new DataSeries('Revenue', [
    new DataPoint('Q1', 10000),
    new DataPoint('Q2', 12000),
    new DataPoint('Q3', 11000),
    new DataPoint('Q4', 15000),
], '#0066CC');

$costs = new DataSeries('Costs', [
    new DataPoint('Q1', 7000),
    new DataPoint('Q2', 8000),
    new DataPoint('Q3', 7500),
    new DataPoint('Q4', 9000),
], '#CC0000');

// Create and configure chart
$chart = new Chart(ChartType::Line);
$chart->setSize(1000, 600)
      ->setFormat(ImageFormat::PNG)
      ->setBackgroundColor('#FFFFFF')
      ->setAxisLabel('x', 'Quarter')
      ->setAxisLabel('y', 'Amount ($)')
      ->setAxisRange('y', 0, 16000)
      ->enableGrid(horizontal: true, vertical: false)
      ->setGridStyle('#EEEEEE', 1)
      ->enableLegend(LegendPosition::TopRight)
      ->addDataSeries($revenue)
      ->addDataSeries($costs)
      ->generate('financial-report.png');

echo "Chart generated successfully!\n";
```

---

## Method Chaining Example

```php
$chart = (new Chart(ChartType::Bar))
    ->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('white')
    ->setAxisColor('black')
    ->enableGrid()
    ->setGridStyle('#DDDDDD')
    ->enableLegend()
    ->addDataSeries($series1)
    ->addDataSeries($series2);

$chart->generate('output.svg');
```

---

## Next Steps

See also:
- [exceptions.md](exceptions.md) - Exception types and error messages
- [color-formats.md](color-formats.md) - Supported color formats
- [../quickstart.md](../quickstart.md) - Quick start guide
- [../data-model.md](../data-model.md) - Complete data model
