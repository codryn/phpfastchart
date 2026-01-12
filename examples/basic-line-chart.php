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

// Create a simple line chart
$chart = new Chart(ChartType::Line);

$salesData = new DataSeries('Monthly Sales', [
    new DataPoint(1.0, 100.0, 'Jan'),
    new DataPoint(2.0, 150.0, 'Feb'),
    new DataPoint(3.0, 120.0, 'Mar'),
    new DataPoint(4.0, 180.0, 'Apr'),
    new DataPoint(5.0, 200.0, 'May'),
    new DataPoint(6.0, 170.0, 'Jun'),
], '#3498db');

$chart->setSize(800, 600)
      ->setFormat(ImageFormat::SVG)
      ->setBackgroundColor('#FFFFFF')
      ->addDataSeries($salesData);

// Generate to file
$outputPath = $outputDir . '/basic_line_chart.svg';
$chart->generate($outputPath);

echo "✅ Chart generated successfully!\n";
echo "📊 Output: {$outputPath}\n";
echo "\nTo view the chart, open the file in a web browser:\n";
echo "  file://{$outputPath}\n";
