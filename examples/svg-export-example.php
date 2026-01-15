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
 * SVG Export Example
 *
 * Demonstrates SVG-specific features and advantages:
 * - Scalable vector graphics (infinite zoom without quality loss)
 * - Small file sizes
 * - Pure XML text format (editable, version-controllable)
 * - Web-friendly (can embed in HTML)
 * - Accessible (can add ARIA labels, semantic markup)
 */

echo "Creating SVG export examples...\n\n";

// Example 1: Basic SVG line chart
echo "1. Creating basic SVG line chart...\n";
$series1 = new DataSeries(
    'Temperature',
    [
        new DataPoint(0, 18, 'Mon'),
        new DataPoint(1, 22, 'Tue'),
        new DataPoint(2, 20, 'Wed'),
        new DataPoint(3, 25, 'Thu'),
        new DataPoint(4, 23, 'Fri'),
        new DataPoint(5, 21, 'Sat'),
        new DataPoint(6, 19, 'Sun'),
    ],
    '#FF6B6B'
);

$chart1 = new Chart(ChartType::Line);
$chart1
    ->setSize(1000, 600)
    ->setFormat(ImageFormat::SVG)
    ->addDataSeries($series1)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Weekly Temperature Trend')
    ->setXAxisLabel( 'Day')
    ->setYAxisLabel( 'Temperature (°C)')
    ->setYAxisRange( 0, 30)
    ->enableGrid(true)
    ->setGridColor('#E0E0E0')->setGridLineWidth(1.0)
    ->generate(__DIR__ . '/output/svg-line-chart.svg');

echo "   ✓ Generated: output/svg-line-chart.svg\n";

// Example 2: Multi-series bar chart in SVG
echo "\n2. Creating multi-series bar chart in SVG...\n";
$barSeries1 = new DataSeries(
    '2023',
    [
        new DataPoint(0, 45, 'Q1'),
        new DataPoint(1, 52, 'Q2'),
        new DataPoint(2, 48, 'Q3'),
        new DataPoint(3, 60, 'Q4'),
    ],
    '#3498DB'
);

$barSeries2 = new DataSeries(
    '2024',
    [
        new DataPoint(0, 50, 'Q1'),
        new DataPoint(1, 58, 'Q2'),
        new DataPoint(2, 55, 'Q3'),
        new DataPoint(3, 68, 'Q4'),
    ],
    '#2ECC71'
);

$chart2 = new Chart(ChartType::Bar);
$chart2
    ->setSize(1000, 600)
    ->setFormat(ImageFormat::SVG)
    ->addDataSeries($barSeries1)
    ->addDataSeries($barSeries2)
    ->setBackgroundColor('#F8F9FA')
    ->setTitle('Quarterly Revenue Comparison')
    ->setXAxisLabel( 'Quarter')
    ->setYAxisLabel( 'Revenue ($M)')
    ->setYAxisRange( 0, 80)
    ->enableGrid(true)
    ->setGridColor('#CCCCCC')->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Right)
    ->generate(__DIR__ . '/output/svg-bar-chart.svg');

echo "   ✓ Generated: output/svg-bar-chart.svg\n";

// Example 3: Scatter plot in SVG
echo "\n3. Creating scatter plot in SVG...\n";
$scatterSeries1 = new DataSeries(
    'Group A',
    [
        new DataPoint(2.5, 4.2),
        new DataPoint(3.8, 6.1),
        new DataPoint(5.2, 5.8),
        new DataPoint(6.5, 7.9),
        new DataPoint(8.1, 9.3),
        new DataPoint(4.5, 7.2),
    ],
    '#9B59B6'
);

$scatterSeries2 = new DataSeries(
    'Group B',
    [
        new DataPoint(3.0, 3.5),
        new DataPoint(4.5, 5.2),
        new DataPoint(6.0, 6.8),
        new DataPoint(7.5, 8.1),
        new DataPoint(9.0, 9.9),
        new DataPoint(5.0, 7.5),
    ],
    '#E67E22'
);

$chart3 = new Chart(ChartType::Scatter);
$chart3
    ->setSize(1000, 600)
    ->setFormat(ImageFormat::SVG)
    ->addDataSeries($scatterSeries1)
    ->addDataSeries($scatterSeries2)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Data Distribution Analysis')
    ->setXAxisLabel( 'X Axis')
    ->setYAxisLabel( 'Y Axis')
    ->setXAxisRange( 0, 10)
    ->setYAxisRange( 0, 10)
    ->enableGrid(true)
    ->setGridColor('#DDDDDD')->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Left)
    ->generate(__DIR__ . '/output/svg-scatter-plot.svg');

echo "   ✓ Generated: output/svg-scatter-plot.svg\n";

// Example 4: Pie chart in SVG
echo "\n4. Creating pie chart in SVG...\n";
$pieSeries = new DataSeries(
    'Browser Market Share',
    [
        new DataPoint(0, 42, 'Chrome'),
        new DataPoint(1, 28, 'Safari'),
        new DataPoint(2, 15, 'Edge'),
        new DataPoint(3, 8, 'Firefox'),
        new DataPoint(4, 7, 'Other'),
    ],
    '#0066CC'
);

$chart4 = new Chart(ChartType::Pie);
$chart4
    ->setSize(800, 800)
    ->setFormat(ImageFormat::SVG)
    ->addDataSeries($pieSeries)
    ->setBackgroundColor('#FAFAFA')
    ->setTitle('Browser Market Share 2024')
    ->enableLegend(LegendPosition::Right)
    ->generate(__DIR__ . '/output/svg-pie-chart.svg');

echo "   ✓ Generated: output/svg-pie-chart.svg\n";

// Example 5: Radar chart in SVG
echo "\n5. Creating radar chart in SVG...\n";
$radarSeries1 = new DataSeries(
    'Product A',
    [
        new DataPoint(0, 9, 'Quality'),
        new DataPoint(1, 7, 'Price'),
        new DataPoint(2, 8, 'Performance'),
        new DataPoint(3, 6, 'Design'),
        new DataPoint(4, 8, 'Support'),
    ],
    '#16A085'
);

$radarSeries2 = new DataSeries(
    'Product B',
    [
        new DataPoint(0, 7, 'Quality'),
        new DataPoint(1, 9, 'Price'),
        new DataPoint(2, 6, 'Performance'),
        new DataPoint(3, 8, 'Design'),
        new DataPoint(4, 7, 'Support'),
    ],
    '#C0392B'
);

$chart5 = new Chart(ChartType::Radar);
$chart5
    ->setSize(800, 800)
    ->setFormat(ImageFormat::SVG)
    ->addDataSeries($radarSeries1)
    ->addDataSeries($radarSeries2)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Product Comparison')
    ->setYAxisRange( 0, 10)
    ->enableGrid(true, false)
    ->setGridColor('#CCCCCC')->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Bottom)
    ->generate(__DIR__ . '/output/svg-radar-chart.svg');

echo "   ✓ Generated: output/svg-radar-chart.svg\n";

// Example 6: Complex multi-series line chart (demonstrating SVG efficiency)
echo "\n6. Creating complex multi-series chart in SVG...\n";
$complexSeries = [];
$colors = ['#E74C3C', '#3498DB', '#2ECC71', '#F39C12', '#9B59B6'];

for ($s = 0; $s < 5; $s++) {
    $points = [];
    for ($i = 0; $i < 50; $i++) {
        $points[] = new DataPoint(
            $i,
            50 + 20 * sin($i / 5 + $s) + rand(-5, 5)
        );
    }
    $complexSeries[] = new DataSeries(
        'Series ' . ($s + 1),
        $points,
        $colors[$s]
    );
}

$chart6 = new Chart(ChartType::Line);
foreach ($complexSeries as $series) {
    $chart6->addDataSeries($series);
}
$chart6
    ->setSize(1400, 700)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#F5F5F5')
    ->setTitle('Complex Multi-Series Data Visualization')
    ->setXAxisLabel( 'Time Period')
    ->setYAxisLabel( 'Value')
    ->setYAxisRange( 0, 100)
    ->enableGrid(true)
    ->setGridColor('#DDDDDD')->setGridLineWidth(1.0)
    ->enableLegend(LegendPosition::Right)
    ->generate(__DIR__ . '/output/svg-complex-chart.svg');

echo "   ✓ Generated: output/svg-complex-chart.svg\n";

// Display SVG advantages
echo "\n✅ All SVG examples created successfully!\n";
echo "\n📊 SVG Format Advantages:\n";
echo "   • Scalable: Zoom infinitely without quality loss\n";
echo "   • Small file size: Text-based format compresses well\n";
echo "   • Editable: Can modify in text editors or design tools\n";
echo "   • Web-friendly: Direct embed in HTML\n";
echo "   • Accessible: Supports ARIA labels and semantic markup\n";
echo "   • Version control friendly: Text diffs work properly\n";
echo "\n💡 Use Cases:\n";
echo "   • Web applications and dashboards\n";
echo "   • Documentation and reports\n";
echo "   • Responsive design (auto-scales)\n";
echo "   • Print materials (high resolution)\n";
echo "   • Interactive visualizations\n";

// Show file sizes
echo "\n📁 Generated SVG files:\n";
$svgFiles = [
    'svg-line-chart.svg',
    'svg-bar-chart.svg',
    'svg-scatter-plot.svg',
    'svg-pie-chart.svg',
    'svg-radar-chart.svg',
    'svg-complex-chart.svg',
];

foreach ($svgFiles as $file) {
    $path = __DIR__ . '/output/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        echo sprintf("   • %-30s %s KB\n", $file, number_format($size / 1024, 2));
    }
}
