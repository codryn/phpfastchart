# Quick Start Guide: PHPFastChart

**Version**: 1.0.0  
**Date**: 2026-01-12

Generate beautiful charts in PHP with minimal code. This guide gets you started in under 5 minutes.

## Installation

```bash
composer require codryn/phpfastchart
```

**Requirements**: PHP 8.1+ with GD extension (standard)

---

## Your First Chart (30 seconds)

```php
<?php declare(strict_types=1);

require 'vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Data\{DataSeries, DataPoint};

// Create data
$sales = new DataSeries('Sales', [
    new DataPoint('Jan', 100),
    new DataPoint('Feb', 150),
    new DataPoint('Mar', 120),
]);

// Generate chart
$chart = new Chart(ChartType::Line);
$chart->addDataSeries($sales)
      ->generate('sales-chart.png');

echo "Chart created: sales-chart.png\n";
```

**Result**: A line chart showing sales trends saved as `sales-chart.png`

---

## Chart Types

### Line Chart

```php
$chart = new Chart(ChartType::Line);
$chart->addDataSeries($series)
      ->generate('line-chart.png');
```

### Bar Chart

```php
$chart = new Chart(ChartType::Bar);
$chart->addDataSeries($series)
      ->generate('bar-chart.png');
```

### Pie Chart

```php
$pie = new DataSeries('Market Share', [
    new DataPoint('Product A', 45),
    new DataPoint('Product B', 30),
    new DataPoint('Product C', 25),
]);

$chart = new Chart(ChartType::Pie);
$chart->addDataSeries($pie)
      ->generate('pie-chart.png');
```

### Scatter Plot

```php
$points = new DataSeries('Measurements', [
    new DataPoint(1.2, 4.5),
    new DataPoint(2.3, 5.1),
    new DataPoint(3.1, 6.2),
]);

$chart = new Chart(ChartType::Scatter);
$chart->addDataSeries($points)
      ->generate('scatter.png');
```

### Radar Chart

```php
$metrics = new DataSeries('Performance', [
    new DataPoint('Speed', 8),
    new DataPoint('Quality', 9),
    new DataPoint('Cost', 6),
    new DataPoint('Support', 7),
]);

$chart = new Chart(ChartType::Radar);
$chart->addDataSeries($metrics)
      ->generate('radar.png');
```

---

## Customization

### Size and Format

```php
use Codryn\PHPFastChart\Configuration\ImageFormat;

$chart->setSize(1200, 800)           // Width x Height
      ->setFormat(ImageFormat::PNG);  // PNG, WEBP, or SVG
```

### Colors

```php
// Background
$chart->setBackgroundColor('#FFFFFF');

// Axis
$chart->setAxisColor('#000000');

// Custom series colors
$series = new DataSeries(
    name: 'Revenue',
    points: $points,
    lineColor: '#FF6600',     // Line or bar color
    fillColor: '#FF660033'    // Area fill (with transparency)
);
```

**Supported color formats**:
- Hex: `#RGB`, `#RRGGBB`, `#RRGGBBAA`
- Named: `red`, `blue`, `green`, `white`, `black`, etc.

### Axes

```php
// Labels
$chart->setAxisLabel('x', 'Month')
      ->setAxisLabel('y', 'Revenue ($)');

// Range
$chart->setAxisRange('y', 0, 100);  // Fixed range
$chart->setAxisRange('y', null, null); // Auto range
```

### Grid Lines

```php
// Enable with auto spacing
$chart->enableGrid();

// Horizontal only
$chart->enableGrid(horizontal: true, vertical: false);

// Custom spacing
$chart->enableGrid(
    horizontal: true,
    vertical: true,
    horizontalSpacing: 10.0,
    verticalSpacing: 5.0
);

// Grid style
$chart->setGridStyle('#DDDDDD', 1);  // Color, line width
```

### Legend

```php
use Codryn\PHPFastChart\Configuration\LegendPosition;

$chart->enableLegend(LegendPosition::TopRight);
// Options: TopLeft, TopRight, BottomLeft, BottomRight, Right
```

---

## Multi-Series Chart

```php
// Create multiple series
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

// Add both series
$chart = new Chart(ChartType::Line);
$chart->setSize(1000, 600)
      ->enableGrid()
      ->enableLegend()
      ->addDataSeries($revenue)
      ->addDataSeries($costs)
      ->generate('financial.png');
```

---

## Output Formats

### PNG (Default)

```php
use Codryn\PHPFastChart\Configuration\ImageFormat;

$chart->setFormat(ImageFormat::PNG)
      ->generate('chart.png');
```

### WEBP (Smaller file size)

```php
$chart->setFormat(ImageFormat::WEBP)
      ->generate('chart.webp');
```

### SVG (Scalable vector)

```php
$chart->setFormat(ImageFormat::SVG)
      ->generate('chart.svg');
```

### Direct Output (No file)

```php
// Get image data as string
$imageData = $chart->render();

// Send to browser
header('Content-Type: ' . $chart->getFormat()->getMimeType());
echo $imageData;

// Or save manually
file_put_contents('chart.png', $imageData);
```

---

## Method Chaining

All configuration methods return `$this` for fluent chaining:

```php
$chart = (new Chart(ChartType::Bar))
    ->setSize(800, 600)
    ->setBackgroundColor('white')
    ->setAxisLabel('x', 'Products')
    ->setAxisLabel('y', 'Sales')
    ->setAxisRange('y', 0, 1000)
    ->enableGrid()
    ->enableLegend(LegendPosition::TopRight)
    ->addDataSeries($series);

$chart->generate('output.png');
```

---

## Error Handling

```php
use Codryn\PHPFastChart\Exception\{
    InvalidArgumentException,
    InvalidConfigurationException,
    RenderException
};

try {
    $chart = new Chart(ChartType::Line);
    $chart->addDataSeries($series)
          ->generate('output.png');
          
} catch (InvalidArgumentException $e) {
    echo "Invalid input: " . $e->getMessage();
    
} catch (InvalidConfigurationException $e) {
    echo "Configuration error: " . $e->getMessage();
    
} catch (RenderException $e) {
    echo "Rendering failed: " . $e->getMessage();
}
```

---

## Common Patterns

### Dynamic Data from Database

```php
// Fetch data from database
$stmt = $pdo->query('SELECT month, revenue FROM sales');
$points = [];

foreach ($stmt as $row) {
    $points[] = new DataPoint($row['month'], (float)$row['revenue']);
}

$series = new DataSeries('Monthly Revenue', $points);
$chart = new Chart(ChartType::Bar);
$chart->addDataSeries($series)->generate('report.png');
```

### Chart with Transparent Background

```php
$chart->setBackgroundColor('#FFFFFF00')  // Fully transparent
      ->setFormat(ImageFormat::PNG)
      ->generate('transparent.png');
```

### Comparison Chart

```php
$actual = new DataSeries('Actual', $actualPoints, '#00CC00');
$target = new DataSeries('Target', $targetPoints, '#0066CC');

$chart = new Chart(ChartType::Line);
$chart->setSize(1200, 600)
      ->setAxisLabel('x', 'Week')
      ->setAxisLabel('y', 'Performance (%)')
      ->setAxisRange('y', 0, 100)
      ->enableGrid(horizontal: true, vertical: false)
      ->enableLegend()
      ->addDataSeries($actual)
      ->addDataSeries($target)
      ->generate('performance.png');
```

---

## Configuration Summary

| Setting | Method | Default |
|---------|--------|---------|
| Size | `setSize(w, h)` | 800x600 |
| Format | `setFormat(format)` | PNG |
| Background | `setBackgroundColor(color)` | White |
| Axis Color | `setAxisColor(color)` | Black |
| Axis Label | `setAxisLabel(axis, label)` | None |
| Axis Range | `setAxisRange(axis, min, max)` | Auto |
| Grid | `enableGrid()` | Disabled |
| Grid Style | `setGridStyle(color, width)` | #CCC, 1px |
| Legend | `enableLegend(position)` | Disabled |

---

## Next Steps

- **Examples**: See `/examples` directory for more complete examples
- **API Reference**: See [contracts/chart-api.md](contracts/chart-api.md)
- **Exception Handling**: See [contracts/exceptions.md](contracts/exceptions.md)
- **Data Model**: See [data-model.md](data-model.md)

## Need Help?

- 📖 [Full Documentation](../README.md)
- 🐛 [Issue Tracker](https://github.com/codryn/phpfastchart/issues)
- 💬 [Discussions](https://github.com/codryn/phpfastchart/discussions)

---

**Happy Charting! 📊**
