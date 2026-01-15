<?php

declare(strict_types=1);

/**
 * Example: Scatter Chart Demonstrations
 *
 * This file shows 6 examples of scatter chart features:
 * 1. Basic scatter chart
 * 2. Multiple series
 * 3. With grid lines
 * 4. With axis scaling
 * 5. With labels
 * 6. Complete example with all features
 *
 * Run: php examples/scatter-chart-example.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Example 1: Basic Scatter Chart
echo "Generating Example 1: Basic Scatter Chart...\n";
$chart1 = new Chart(ChartType::Scatter);
$series1 = new DataSeries('Data Points', [
    new DataPoint(5.0, 10.0),
    new DataPoint(15.0, 25.0),
    new DataPoint(20.0, 15.0),
    new DataPoint(30.0, 35.0),
    new DataPoint(25.0, 20.0),
]);

$chart1->setFormat(ImageFormat::SVG)
    ->setSize(600, 400)
    ->addDataSeries($series1);

file_put_contents(__DIR__ . '/output/scatter-basic.svg', $chart1->render());
echo "✓ Created: examples/output/scatter-basic.svg\n\n";

// Example 2: Multiple Series
echo "Generating Example 2: Multiple Series...\n";
$chart2 = new Chart(ChartType::Scatter);

$series2a = new DataSeries('Group A', [
    new DataPoint(10.0, 20.0),
    new DataPoint(20.0, 30.0),
    new DataPoint(30.0, 25.0),
    new DataPoint(40.0, 35.0),
], '#FF5733');

$series2b = new DataSeries('Group B', [
    new DataPoint(15.0, 35.0),
    new DataPoint(25.0, 40.0),
    new DataPoint(35.0, 38.0),
    new DataPoint(45.0, 42.0),
], '#33C1FF');

$series2c = new DataSeries('Group C', [
    new DataPoint(12.0, 15.0),
    new DataPoint(22.0, 18.0),
    new DataPoint(32.0, 20.0),
    new DataPoint(42.0, 22.0),
], '#28A745');

$chart2->setFormat(ImageFormat::SVG)
    ->setSize(600, 400)
    ->addDataSeries($series2a)
    ->addDataSeries($series2b)
    ->addDataSeries($series2c);

file_put_contents(__DIR__ . '/output/scatter-multiple-series.svg', $chart2->render());
echo "✓ Created: examples/output/scatter-multiple-series.svg\n\n";

// Example 3: With Grid Lines
echo "Generating Example 3: With Grid Lines...\n";
$chart3 = new Chart(ChartType::Scatter);
$series3 = new DataSeries('Measurements', [
    new DataPoint(5.0, 10.0),
    new DataPoint(10.0, 20.0),
    new DataPoint(15.0, 15.0),
    new DataPoint(20.0, 25.0),
    new DataPoint(25.0, 22.0),
    new DataPoint(30.0, 28.0),
]);

$chart3->setFormat(ImageFormat::SVG)
    ->setSize(600, 400)
    ->enableGrid()
    ->setGridColor('#666666')
    ->addDataSeries($series3);

file_put_contents(__DIR__ . '/output/scatter-with-grid.svg', $chart3->render());
echo "✓ Created: examples/output/scatter-with-grid.svg\n\n";

// Example 4: With Axis Scaling
echo "Generating Example 4: With Axis Scaling...\n";
$chart4 = new Chart(ChartType::Scatter);
$series4 = new DataSeries('Scaled Data', [
    new DataPoint(25.0, 50.0),
    new DataPoint(50.0, 75.0),
    new DataPoint(75.0, 60.0),
    new DataPoint(40.0, 55.0),
    new DataPoint(60.0, 70.0),
]);

$chart4->setFormat(ImageFormat::SVG)
    ->setSize(600, 400)
    ->setXAxisRange(0.0, 100.0)
    ->setYAxisRange(0.0, 100.0)
    ->addDataSeries($series4);

file_put_contents(__DIR__ . '/output/scatter-with-scaling.svg', $chart4->render());
echo "✓ Created: examples/output/scatter-with-scaling.svg\n\n";

// Example 5: With Labels
echo "Generating Example 5: With Labels...\n";
$chart5 = new Chart(ChartType::Scatter);
$series5 = new DataSeries('Temperature vs Humidity', [
    new DataPoint(18.5, 65.0),
    new DataPoint(22.3, 58.0),
    new DataPoint(25.8, 52.0),
    new DataPoint(28.1, 48.0),
    new DataPoint(20.0, 62.0),
]);

$chart5->setFormat(ImageFormat::SVG)
    ->setSize(600, 400)
    ->setTitle('Weather Correlation')
    ->setXAxisLabel('Temperature (°C)')
    ->setYAxisLabel('Humidity (%)')
    ->addDataSeries($series5);

file_put_contents(__DIR__ . '/output/scatter-with-labels.svg', $chart5->render());
echo "✓ Created: examples/output/scatter-with-labels.svg\n\n";

// Example 6: Complete Example (All Features)
echo "Generating Example 6: Complete Example...\n";
$chart6 = new Chart(ChartType::Scatter);

$series6a = new DataSeries('Product A', [
    new DataPoint(10.0, 85.0),
    new DataPoint(15.0, 90.0),
    new DataPoint(20.0, 88.0),
    new DataPoint(25.0, 92.0),
    new DataPoint(30.0, 95.0),
], '#FF6384');

$series6b = new DataSeries('Product B', [
    new DataPoint(12.0, 70.0),
    new DataPoint(18.0, 75.0),
    new DataPoint(22.0, 72.0),
    new DataPoint(28.0, 78.0),
    new DataPoint(32.0, 80.0),
], '#36A2EB');

$chart6->setFormat(ImageFormat::SVG)
    ->setSize(800, 600)
    ->setTitle('Product Performance: Price vs Customer Satisfaction')
    ->setXAxisLabel('Price ($)')
    ->setYAxisLabel('Satisfaction Score')
    ->setXAxisRange(0.0, 40.0)
    ->setYAxisRange(0.0, 100.0)
    ->enableGrid()
    ->setGridColor('#666666')
    ->setBackgroundColor('#FFFFFF')
    ->setAxisColor('#333333')
    ->enableDataLabels()
    ->addDataSeries($series6a)
    ->addDataSeries($series6b);

file_put_contents(__DIR__ . '/output/scatter-complete.svg', $chart6->render());
echo "✓ Created: examples/output/scatter-complete.svg\n\n";

echo "All scatter chart examples generated successfully!\n";
echo "View the SVG files in examples/output/ directory\n";
