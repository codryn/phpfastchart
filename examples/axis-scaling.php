<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\AxisClipMode;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Create output directory if it doesn't exist
$outputDir = __DIR__ . '/output';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

echo "=== Axis Scaling Examples ===\n\n";

// Example 1: Auto-scaling (default behavior)
echo "1. Auto-scaling chart (no explicit ranges)\n";
$chart1 = new Chart(ChartType::Line);

$sales = new DataSeries('Sales', [
    new DataPoint(1.0, 150.0),
    new DataPoint(2.0, 180.0),
    new DataPoint(3.0, 210.0),
    new DataPoint(4.0, 195.0),
    new DataPoint(5.0, 230.0),
], '#2ECC71');

$chart1->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->addDataSeries($sales);

$chart1->generate($outputDir . '/axis_auto_scale.svg');
echo "   Generated: axis_auto_scale.svg (auto-scaled to fit data)\n\n";

// Example 2: Manual Y-axis range
echo "2. Chart with manual Y-axis range [0, 300]\n";
$chart2 = new Chart(ChartType::Line);

$chart2->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->setYAxisRange(0.0, 300.0)
    ->addDataSeries($sales);

$chart2->generate($outputDir . '/axis_manual_y.svg');
echo "   Generated: axis_manual_y.svg (Y-axis fixed to 0-300)\n\n";

// Example 3: Manual X and Y axis ranges
echo "3. Chart with both X and Y axis ranges\n";
$chart3 = new Chart(ChartType::Bar);

$quarterly = new DataSeries('Q1-Q4', [
    new DataPoint(1.0, 85.0),
    new DataPoint(2.0, 120.0),
    new DataPoint(3.0, 95.0),
    new DataPoint(4.0, 140.0),
], '#3498DB');

$chart3->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#F8F9FA')
    ->setXAxisRange(0.0, 5.0)
    ->setYAxisRange(0.0, 200.0)
    ->addDataSeries($quarterly);

$chart3->generate($outputDir . '/axis_manual_both.svg');
echo "   Generated: axis_manual_both.svg (X: 0-5, Y: 0-200)\n\n";

// Example 4: Clip mode - data outside range is clipped
echo "4. Chart with clip mode (data exceeds Y range but is clipped)\n";
$chart4 = new Chart(ChartType::Line);

$volatile = new DataSeries('Volatile Data', [
    new DataPoint(0.0, 50.0),
    new DataPoint(1.0, 150.0),  // Exceeds Y max (100)
    new DataPoint(2.0, 75.0),
    new DataPoint(3.0, -20.0),  // Below Y min (0)
    new DataPoint(4.0, 90.0),
], '#E74C3C');

$chart4->setSize(800, 600)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->setYAxisRange(0.0, 100.0)
    ->setAxisClipMode(AxisClipMode::Clip)
    ->addDataSeries($volatile);

$chart4->generate($outputDir . '/axis_clip_mode.svg');
echo "   Generated: axis_clip_mode.svg (out-of-range data clipped to 0-100)\n\n";

// Example 5: Throw mode demonstration (commented out - would throw exception)
echo "5. Throw mode example (commented out - would throw exception):\n";
echo "   /*\n";
echo "   \$chart5 = new Chart(ChartType::Line);\n";
echo "   \$chart5->setYAxisRange(0.0, 100.0)\n";
echo "          ->setAxisClipMode(AxisClipMode::Throw)\n";
echo "          ->addDataSeries(\$volatile);\n";
echo "   \$chart5->generate(\$outputDir . '/axis_throw_mode.svg');\n";
echo "   // Would throw: InvalidArgumentException - data point outside range\n";
echo "   */\n\n";

// Example 6: Consistent comparison across multiple charts
echo "6. Multiple charts with same axis ranges for comparison\n";

$product1 = new DataSeries('Product A', [
    new DataPoint(1.0, 20.0),
    new DataPoint(2.0, 35.0),
    new DataPoint(3.0, 40.0),
    new DataPoint(4.0, 30.0),
], '#9B59B6');

$product2 = new DataSeries('Product B', [
    new DataPoint(1.0, 50.0),
    new DataPoint(2.0, 65.0),
    new DataPoint(3.0, 70.0),
    new DataPoint(4.0, 85.0),
], '#F39C12');

// Both charts use same axis ranges for easy comparison
$chart6a = new Chart(ChartType::Line);
$chart6a->setSize(800, 400)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->setXAxisRange(0.0, 5.0)
    ->setYAxisRange(0.0, 100.0)
    ->addDataSeries($product1);
$chart6a->generate($outputDir . '/axis_compare_a.svg');

$chart6b = new Chart(ChartType::Line);
$chart6b->setSize(800, 400)
    ->setFormat(ImageFormat::SVG)
    ->setBackgroundColor('#FFFFFF')
    ->setXAxisRange(0.0, 5.0)
    ->setYAxisRange(0.0, 100.0)
    ->addDataSeries($product2);
$chart6b->generate($outputDir . '/axis_compare_b.svg');

echo "   Generated: axis_compare_a.svg and axis_compare_b.svg\n";
echo "   (same axis ranges allow direct comparison)\n\n";

echo "✓ All axis scaling examples generated successfully!\n";
echo "\nKey Features Demonstrated:\n";
echo "  • Auto-scaling (charts adjust to fit data)\n";
echo "  • Manual X-axis range (setXAxisRange)\n";
echo "  • Manual Y-axis range (setYAxisRange)\n";
echo "  • Clip mode (constrain out-of-range data)\n";
echo "  • Throw mode (exception for out-of-range data)\n";
echo "  • Consistent ranges for chart comparison\n";
