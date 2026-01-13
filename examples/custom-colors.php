<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Create output directory
$outputDir = __DIR__ . '/output';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// Create a chart with custom colors
$chart = new Chart(ChartType::Line);

$series1 = new DataSeries('Revenue', [
    new DataPoint(1.0, 100.0),
    new DataPoint(2.0, 150.0),
    new DataPoint(3.0, 130.0),
    new DataPoint(4.0, 180.0),
], '#3498db');

$series2 = new DataSeries('Costs', [
    new DataPoint(1.0, 60.0),
    new DataPoint(2.0, 80.0),
    new DataPoint(3.0, 70.0),
    new DataPoint(4.0, 90.0),
], '#e74c3c');

$chart->setSize(1000, 700)
      ->setFormat(ImageFormat::SVG)
      ->setBackgroundColor('#f8f9fa')
      ->setAxisColor('#2c3e50')
      ->addDataSeries($series1)
      ->addDataSeries($series2);

// Generate to file
$outputPath = $outputDir . '/custom_colors.svg';
$chart->generate($outputPath);

echo "✅ Custom colors chart generated successfully!\n";
echo "📊 Output: {$outputPath}\n";
echo "\nColors used:\n";
echo "  - Background: #f8f9fa (light gray)\n";
echo "  - Axes: #2c3e50 (dark blue-gray)\n";
echo "  - Revenue line: #3498db (blue)\n";
echo "  - Costs line: #e74c3c (red)\n";
