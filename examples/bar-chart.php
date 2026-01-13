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

// Create a bar chart
$chart = new Chart(ChartType::Bar);

$productSales = new DataSeries('Product Sales', [
    new DataPoint(1.0, 120.0, 'Product A'),
    new DataPoint(2.0, 180.0, 'Product B'),
    new DataPoint(3.0, 150.0, 'Product C'),
    new DataPoint(4.0, 200.0, 'Product D'),
    new DataPoint(5.0, 90.0, 'Product E'),
], '#2ecc71');

$chart->setSize(800, 600)
      ->setFormat(ImageFormat::SVG)
      ->setBackgroundColor('#FFFFFF')
      ->setAxisColor('#333333')
      ->addDataSeries($productSales);

// Generate to file
$outputPath = $outputDir . '/bar_chart.svg';
$chart->generate($outputPath);

echo "✅ Bar chart generated successfully!\n";
echo "📊 Output: {$outputPath}\n";
