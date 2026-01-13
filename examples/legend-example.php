<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\LegendPosition;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Example 1: Basic legend with multiple series
echo "Example 1: Line chart with legend enabled\n";
$chart1 = new Chart(ChartType::Line);
$chart1->setTitle('Sales Data with Legend')
    ->setXAxisLabel('Month')
    ->setYAxisLabel('Sales ($)')
    ->enableLegend();

$chart1->addDataSeries(
    new DataSeries(
        'Product A',
        [
            new DataPoint(1, 1000),
            new DataPoint(2, 1500),
            new DataPoint(3, 1200),
            new DataPoint(4, 1800),
        ],
        '#FF6384'
    )
);

$chart1->addDataSeries(
    new DataSeries(
        'Product B',
        [
            new DataPoint(1, 800),
            new DataPoint(2, 1100),
            new DataPoint(3, 1400),
            new DataPoint(4, 1600),
        ],
        '#36A2EB'
    )
);

$chart1->generate(__DIR__ . '/output/legend-basic.svg');
echo "✓ Generated: examples/output/legend-basic.svg\n\n";

// Example 2: Legend at right position (default)
echo "Example 2: Legend at right position\n";
$chart2 = new Chart(ChartType::Line);
$chart2->setTitle('Legend at Right (Default)')
    ->enableLegend();

$chart2->addDataSeries(new DataSeries('Series 1', [
    new DataPoint(1, 100),
    new DataPoint(2, 200),
    new DataPoint(3, 150),
], '#FF6384'));

$chart2->addDataSeries(new DataSeries('Series 2', [
    new DataPoint(1, 150),
    new DataPoint(2, 180),
    new DataPoint(3, 220),
], '#36A2EB'));

$chart2->generate(__DIR__ . '/output/legend-right.svg');
echo "✓ Generated: examples/output/legend-right.svg\n\n";

// Example 3: Legend at bottom position
echo "Example 3: Legend at bottom position\n";
$chart3 = new Chart(ChartType::Line);
$chart3->setTitle('Legend at Bottom')
    ->enableLegend()
    ->setLegendPosition(LegendPosition::Bottom);

$chart3->addDataSeries(new DataSeries('Series 1', [
    new DataPoint(1, 100),
    new DataPoint(2, 200),
    new DataPoint(3, 150),
], '#FF6384'));

$chart3->addDataSeries(new DataSeries('Series 2', [
    new DataPoint(1, 150),
    new DataPoint(2, 180),
    new DataPoint(3, 220),
], '#36A2EB'));

$chart3->generate(__DIR__ . '/output/legend-bottom.svg');
echo "✓ Generated: examples/output/legend-bottom.svg\n\n";

// Example 4: Bar chart with legend
echo "Example 4: Bar chart with legend\n";
$chart4 = new Chart(ChartType::Bar);
$chart4->setTitle('Quarterly Sales Comparison')
    ->setXAxisLabel('Quarter')
    ->setYAxisLabel('Revenue ($1000s)')
    ->enableLegend()
    ->setLegendPosition(LegendPosition::Top);

$chart4->addDataSeries(new DataSeries('Q1 2023', [
    new DataPoint(1, 50),
    new DataPoint(2, 70),
    new DataPoint(3, 60),
], '#FF6384'));

$chart4->addDataSeries(new DataSeries('Q2 2023', [
    new DataPoint(1, 65),
    new DataPoint(2, 85),
    new DataPoint(3, 75),
], '#36A2EB'));

$chart4->generate(__DIR__ . '/output/legend-bar.svg');
echo "✓ Generated: examples/output/legend-bar.svg\n\n";

// Example 5: Custom legend styling
echo "Example 5: Legend with custom styling\n";
$chart5 = new Chart(ChartType::Line);
$chart5->setTitle('Custom Legend Style')
    ->enableLegend()
    ->setLegendTextColor('#FFFFFF')
    ->setLegendBackgroundColor('#2C3E50')
    ->setLegendBorderColor('#E74C3C');

$chart5->addDataSeries(new DataSeries('Dataset 1', [
    new DataPoint(1, 100),
    new DataPoint(2, 200),
    new DataPoint(3, 150),
    new DataPoint(4, 250),
], '#3498DB'));

$chart5->addDataSeries(new DataSeries('Dataset 2', [
    new DataPoint(1, 80),
    new DataPoint(2, 160),
    new DataPoint(3, 120),
    new DataPoint(4, 200),
], '#2ECC71'));

$chart5->generate(__DIR__ . '/output/legend-custom-style.svg');
echo "✓ Generated: examples/output/legend-custom-style.svg\n\n";

// Example 6: Chart without legend (disabled by default)
echo "Example 6: Chart without legend\n";
$chart6 = new Chart(ChartType::Line);
$chart6->setTitle('Chart without Legend');
// Legend is disabled by default, no need to call disableLegend()

$chart6->addDataSeries(new DataSeries('Data', [
    new DataPoint(1, 100),
    new DataPoint(2, 200),
    new DataPoint(3, 150),
], '#3498DB'));

$chart6->generate(__DIR__ . '/output/legend-disabled.svg');
echo "✓ Generated: examples/output/legend-disabled.svg\n\n";

echo "All legend examples generated successfully!\n";
echo "View the SVG files in the examples/output/ directory.\n";
