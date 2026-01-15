<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Configuration\LegendPosition;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

/**
 * Advanced Styling Example
 *
 * Demonstrates comprehensive usage of all PHPFastChart features:
 * - Multiple data series
 * - Custom colors
 * - Grid lines
 * - Axis scaling
 * - Labels and titles
 * - Legend
 * - All output formats
 */

echo "Creating advanced styled chart with all features...\n\n";

// Create multi-series data
$series1 = new DataSeries(
    'Revenue',
    [
        new DataPoint(0, 120, 'Q1 2023'),
        new DataPoint(1, 150, 'Q2 2023'),
        new DataPoint(2, 135, 'Q3 2023'),
        new DataPoint(3, 180, 'Q4 2023'),
        new DataPoint(4, 165, 'Q1 2024'),
        new DataPoint(5, 195, 'Q2 2024'),
    ],
    '#0066CC'
);

$series2 = new DataSeries(
    'Expenses',
    [
        new DataPoint(0, 80, 'Q1 2023'),
        new DataPoint(1, 95, 'Q2 2023'),
        new DataPoint(2, 90, 'Q3 2023'),
        new DataPoint(3, 110, 'Q4 2023'),
        new DataPoint(4, 105, 'Q1 2024'),
        new DataPoint(5, 120, 'Q2 2024'),
    ],
    '#FF6600'
);

$series3 = new DataSeries(
    'Profit',
    [
        new DataPoint(0, 40, 'Q1 2023'),
        new DataPoint(1, 55, 'Q2 2023'),
        new DataPoint(2, 45, 'Q3 2023'),
        new DataPoint(3, 70, 'Q4 2023'),
        new DataPoint(4, 60, 'Q1 2024'),
        new DataPoint(5, 75, 'Q2 2024'),
    ],
    '#00AA44'
);

// Create chart with full styling - PNG format
echo "1. Creating PNG with all features...\n";
$chartPng = new Chart(ChartType::Line);
$chartPng
    ->setSize(1200, 800)
    ->setFormat(ImageFormat::PNG)
    ->addDataSeries($series1)
    ->addDataSeries($series2)
    ->addDataSeries($series3)
    ->setBackgroundColor('#F5F5F5')
    ->setTitle('Company Financial Performance')
    ->setXAxisLabel('Quarter')
    ->setYAxisLabel('Amount ($1000s)')
    ->setYAxisRange(0, 200)
    ->enableGrid(true, true)
    ->setGridColor('#CCCCCC')
    ->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Right)
    ->generate(__DIR__ . '/output/advanced-styling.png');

echo "   ✓ Generated: output/advanced-styling.png\n";

// Note: WEBP format requires imagewebp() function support in GD library
// Skipping WEBP generation in this example (not available in all PHP builds)

// Create same chart in SVG format
echo "2. Creating SVG with all features...\n";
$chartSvg = new Chart(ChartType::Line);
$chartSvg
    ->setSize(1200, 800)
    ->setFormat(ImageFormat::SVG)
    ->addDataSeries($series1)
    ->addDataSeries($series2)
    ->addDataSeries($series3)
    ->setBackgroundColor('#F5F5F5')
    ->setTitle('Company Financial Performance')
    ->setXAxisLabel('Quarter')
    ->setYAxisLabel('Amount ($1000s)')
    ->setYAxisRange(0, 200)
    ->enableGrid(true, true)
    ->setGridColor('#CCCCCC')
    ->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Right)
    ->generate(__DIR__ . '/output/advanced-styling.svg');

echo "   ✓ Generated: output/advanced-styling.svg\n";

// Create bar chart with custom styling
echo "\n3. Creating styled bar chart...\n";
$barSeries = new DataSeries(
    'Sales by Region',
    [
        new DataPoint(0, 450, 'North'),
        new DataPoint(1, 380, 'South'),
        new DataPoint(2, 520, 'East'),
        new DataPoint(3, 410, 'West'),
        new DataPoint(4, 490, 'Central'),
    ],
    '#3366CC'
);

$barChart = new Chart(ChartType::Bar);
$barChart
    ->setSize(1000, 700)
    ->setFormat(ImageFormat::PNG)
    ->addDataSeries($barSeries)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Regional Sales Analysis')
    ->setXAxisLabel('Region')
    ->setYAxisLabel('Sales ($1000s)')
    ->setYAxisRange(0, 600)
    ->enableGrid(false, true)
    ->setGridColor('#E0E0E0')
    ->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Top)
    ->generate(__DIR__ . '/output/advanced-bar-chart.png');

echo "   ✓ Generated: output/advanced-bar-chart.png\n";

// Create scatter plot with custom styling
echo "\n5. Creating styled scatter plot...\n";
$scatterSeries1 = new DataSeries(
    'Dataset A',
    [
        new DataPoint(1.5, 3.2),
        new DataPoint(2.8, 5.1),
        new DataPoint(4.2, 4.8),
        new DataPoint(5.5, 6.9),
        new DataPoint(7.1, 8.3),
        new DataPoint(3.5, 7.2),
        new DataPoint(6.2, 5.5),
    ],
    '#FF6B6B'
);

$scatterSeries2 = new DataSeries(
    'Dataset B',
    [
        new DataPoint(2.0, 2.5),
        new DataPoint(3.5, 4.2),
        new DataPoint(5.0, 5.8),
        new DataPoint(6.5, 7.1),
        new DataPoint(8.0, 8.9),
        new DataPoint(4.0, 6.5),
        new DataPoint(7.0, 4.8),
    ],
    '#4ECDC4'
);

$scatterChart = new Chart(ChartType::Scatter);
$scatterChart
    ->setSize(1000, 700)
    ->setFormat(ImageFormat::PNG)
    ->addDataSeries($scatterSeries1)
    ->addDataSeries($scatterSeries2)
    ->setBackgroundColor('#FAFAFA')
    ->setTitle('Correlation Analysis')
    ->setXAxisLabel('Variable X')
    ->setYAxisLabel('Variable Y')
    ->setXAxisRange(0, 10)
    ->setYAxisRange(0, 10)
    ->enableGrid(true, true)
    ->setGridColor('#DDDDDD')
    ->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Bottom)
    ->generate(__DIR__ . '/output/advanced-scatter-plot.png');

echo "   ✓ Generated: output/advanced-scatter-plot.png\n";

// Create pie chart with custom styling
echo "\n6. Creating styled pie chart...\n";
$pieSeries = new DataSeries(
    'Market Share',
    [
        new DataPoint(0, 35, 'Product A'),
        new DataPoint(1, 25, 'Product B'),
        new DataPoint(2, 20, 'Product C'),
        new DataPoint(3, 12, 'Product D'),
        new DataPoint(4, 8, 'Others'),
    ],
    '#0066CC'
);

$pieChart = new Chart(ChartType::Pie);
$pieChart
    ->setSize(800, 800)
    ->setFormat(ImageFormat::PNG)
    ->addDataSeries($pieSeries)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Market Share Distribution')
    ->enableLegend(LegendPosition::Right)
    ->generate(__DIR__ . '/output/advanced-pie-chart.png');

echo "   ✓ Generated: output/advanced-pie-chart.png\n";

// Create radar chart with custom styling
echo "\n7. Creating styled radar chart...\n";
$radarSeries1 = new DataSeries(
    'Team Alpha',
    [
        new DataPoint(0, 8, 'Speed'),
        new DataPoint(1, 7, 'Strength'),
        new DataPoint(2, 9, 'Agility'),
        new DataPoint(3, 6, 'Defense'),
        new DataPoint(4, 8, 'Stamina'),
        new DataPoint(5, 7, 'Intelligence'),
    ],
    '#9B59B6'
);

$radarSeries2 = new DataSeries(
    'Team Beta',
    [
        new DataPoint(0, 6, 'Speed'),
        new DataPoint(1, 9, 'Strength'),
        new DataPoint(2, 7, 'Agility'),
        new DataPoint(3, 8, 'Defense'),
        new DataPoint(4, 7, 'Stamina'),
        new DataPoint(5, 8, 'Intelligence'),
    ],
    '#E67E22'
);

$radarChart = new Chart(ChartType::Radar);
$radarChart
    ->setSize(800, 800)
    ->setFormat(ImageFormat::PNG)
    ->addDataSeries($radarSeries1)
    ->addDataSeries($radarSeries2)
    ->setBackgroundColor('#F8F9FA')
    ->setTitle('Team Capabilities Comparison')
    ->setYAxisRange(0, 10)
    ->enableGrid(true, false)
    ->setGridColor('#CCCCCC')
    ->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Bottom)
    ->generate(__DIR__ . '/output/advanced-radar-chart.png');

echo "   ✓ Generated: output/advanced-radar-chart.png\n";

echo "\n✅ All advanced styling examples created successfully!\n";
echo "Check the 'output' directory for generated charts.\n";
