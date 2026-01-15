<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Create output directory if it doesn't exist
$outputDir = __DIR__ . '/output';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

echo "=== Labels and Text Examples ===\n\n";

// Example 1: Chart with title only
echo "1. Chart with title\n";
$chart1 = new Chart(ChartType::Line);

$sales = new DataSeries('Monthly Sales', [
    new DataPoint(1.0, 125.0),
    new DataPoint(2.0, 142.0),
    new DataPoint(3.0, 138.0),
    new DataPoint(4.0, 165.0),
    new DataPoint(5.0, 178.0),
    new DataPoint(6.0, 190.0),
], '#2ECC71');

$chart1->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Sales Performance 2024')
    ->addDataSeries($sales);

$chart1->generate($outputDir . '/labels_title_only.svg');
echo "   Generated: labels_title_only.svg\n\n";

// Example 2: Chart with axis labels
echo "2. Chart with X and Y axis labels\n";
$chart2 = new Chart(ChartType::Bar);

$revenue = new DataSeries('Revenue', [
    new DataPoint(1.0, 45.0),
    new DataPoint(2.0, 52.0),
    new DataPoint(3.0, 48.0),
    new DataPoint(4.0, 61.0),
], '#3498DB');

$chart2->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#F8F9FA')
    ->setXAxisLabel('Quarter')
    ->setYAxisLabel('Revenue (Million $)')
    ->addDataSeries($revenue);

$chart2->generate($outputDir . '/labels_axis_only.svg');
echo "   Generated: labels_axis_only.svg\n\n";

// Example 3: Chart with data point labels
echo "3. Chart with data point labels\n";
$chart3 = new Chart(ChartType::Line);

$temperature = new DataSeries('Temperature', [
    new DataPoint(0.0, 18.5),
    new DataPoint(2.0, 22.3),
    new DataPoint(4.0, 25.8),
    new DataPoint(6.0, 28.1),
    new DataPoint(8.0, 26.4),
    new DataPoint(10.0, 23.7),
], '#E74C3C');

$chart3->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->enableDataLabels()
    ->addDataSeries($temperature);

$chart3->generate($outputDir . '/labels_data_points.svg');
echo "   Generated: labels_data_points.svg\n\n";

// Example 4: Complete chart with all labels
echo "4. Complete chart with title, axis labels, and data labels\n";
$chart4 = new Chart(ChartType::Bar);

$production = new DataSeries('Units Produced', [
    new DataPoint(1.0, 850.0),
    new DataPoint(2.0, 920.0),
    new DataPoint(3.0, 1050.0),
    new DataPoint(4.0, 980.0),
    new DataPoint(5.0, 1120.0),
], '#9B59B6');

$chart4->setSize(900, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Factory Production Q1-Q2 2024')
    ->setXAxisLabel('Month')
    ->setYAxisLabel('Units')
    ->enableDataLabels()
    ->addDataSeries($production);

$chart4->generate($outputDir . '/labels_complete.svg');
echo "   Generated: labels_complete.svg\n\n";

// Example 5: Multi-series chart with labels
echo "5. Multi-series chart with all labels\n";
$chart5 = new Chart(ChartType::Line);

$actual = new DataSeries('Actual', [
    new DataPoint(1.0, 75.0),
    new DataPoint(2.0, 82.0),
    new DataPoint(3.0, 88.0),
    new DataPoint(4.0, 95.0),
], '#2ECC71');

$target = new DataSeries('Target', [
    new DataPoint(1.0, 80.0),
    new DataPoint(2.0, 85.0),
    new DataPoint(3.0, 90.0),
    new DataPoint(4.0, 95.0),
], '#E67E22');

$chart5->setSize(900, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Performance vs Target')
    ->setXAxisLabel('Quarter')
    ->setYAxisLabel('Achievement (%)')
    ->enableDataLabels()
    ->addDataSeries($actual)
    ->addDataSeries($target);

$chart5->generate($outputDir . '/labels_multi_series.svg');
echo "   Generated: labels_multi_series.svg\n\n";

// Example 6: Bar chart with grid and labels
echo "6. Bar chart with grid lines and labels\n";
$chart6 = new Chart(ChartType::Bar);

$costs = new DataSeries('Operating Costs', [
    new DataPoint(1.0, 125.0),
    new DataPoint(2.0, 108.0),
    new DataPoint(3.0, 142.0),
    new DataPoint(4.0, 95.0),
], '#34495E');

$chart6->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->setTitle('Quarterly Operating Costs')
    ->setXAxisLabel('Quarter')
    ->setYAxisLabel('Cost ($1000s)')
    ->enableGrid()
    ->setGridColor('#666666')
    ->enableDataLabels()
    ->addDataSeries($costs);

$chart6->generate($outputDir . '/labels_with_grid.svg');
echo "   Generated: labels_with_grid.svg\n\n";

echo "✓ All label examples generated successfully!\n";
echo "\nKey Features Demonstrated:\n";
echo "  • Chart title (setTitle)\n";
echo "  • X-axis label (setXAxisLabel)\n";
echo "  • Y-axis label (setYAxisLabel)\n";
echo "  • Data point labels (enableDataLabels)\n";
echo "  • Multi-series charts with labels\n";
echo "  • Labels combined with grid lines\n";
