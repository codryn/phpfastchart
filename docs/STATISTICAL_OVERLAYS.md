# Statistical Overlays

## Overview

Statistical overlays allow you to display statistical data (minimum, maximum, average, and standard deviation) as horizontal lines with labels over your chart data.

## Features

- Displays min, max, and average as horizontal lines across the chart
- Each overlay can have a custom color
- Works with X/Y chart types: Line, Bar, and Scatter charts
- Multiple overlays can be added to compare different statistics
- Automatically throws exceptions for non-X/Y chart types (Pie, Radar)

## Usage

### Basic Example

```php
use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Data\StatisticalOverlay;

// Create chart with data
$chart = new Chart(ChartType::Bar);
$series = new DataSeries('Sales', [
    new DataPoint(1.0, 100.0, 'Q1'),
    new DataPoint(2.0, 150.0, 'Q2'),
    new DataPoint(3.0, 120.0, 'Q3'),
    new DataPoint(4.0, 180.0, 'Q4'),
], '#3498db');

// Create statistical overlay
$overlay = new StatisticalOverlay(
    min: 100.0,
    max: 180.0,
    average: 137.5,
    stdDeviation: 32.02,
    color: '#e74c3c'  // Red color for the overlay
);

// Add to chart
$chart->addDataSeries($series)
      ->addStatisticalOverlay($overlay)
      ->generate('chart_with_overlay.svg');
```

### Multiple Overlays

You can add multiple overlays to compare different statistics:

```php
// Overlay 1: Actual statistics from data
$actualOverlay = new StatisticalOverlay(
    min: 100.0,
    max: 180.0,
    average: 137.5,
    stdDeviation: 32.02,
    color: '#e74c3c'  // Red
);

// Overlay 2: Target/theoretical statistics
$targetOverlay = new StatisticalOverlay(
    min: 110.0,
    max: 190.0,
    average: 150.0,
    stdDeviation: 30.0,
    color: '#2ecc71'  // Green
);

$chart->addDataSeries($series)
      ->addStatisticalOverlay($actualOverlay)
      ->addStatisticalOverlay($targetOverlay)
      ->generate('chart_with_multiple_overlays.svg');
```

## Visualization

The overlay renders:
- **Min line**: Dashed horizontal line with "min=X.XX" label
- **Max line**: Dashed horizontal line with "max=X.XX" label  
- **Avg line**: Solid horizontal line with "avg=X.XX" label

All lines use the color specified in the StatisticalOverlay constructor.

## Supported Chart Types

✅ **Supported:**
- Line charts (`ChartType::Line`)
- Bar charts (`ChartType::Bar`)
- Scatter charts (`ChartType::Scatter`)

❌ **Not Supported:**
- Pie charts (`ChartType::Pie`) - throws `InvalidArgumentException`
- Radar charts (`ChartType::Radar`) - throws `InvalidArgumentException`

## Complete Example: Dice Roll Statistics

See `examples/dice-roll-statistics.php` for a complete working example that simulates rolling dice and overlays both actual and theoretical statistics.

## API Reference

### StatisticalOverlay Constructor

```php
public function __construct(
    float $min,              // Minimum value
    float $max,              // Maximum value  
    float $average,          // Average value
    float $stdDeviation,     // Standard deviation
    string $color = '#FF0000' // Hex color (default: red)
)
```

**Validation:**
- `$min` must be less than or equal to `$max`
- `$stdDeviation` must be non-negative

### Chart::addStatisticalOverlay()

```php
public function addStatisticalOverlay(
    StatisticalOverlay $overlay
): self
```

**Throws:**
- `InvalidArgumentException` if called on Pie or Radar chart types
