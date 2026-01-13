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

// Create a line chart with grid lines
$chart = new Chart(ChartType::Line);

$series1 = new DataSeries('Temperature', [
    new DataPoint(0.0, 15.0),
    new DataPoint(10.0, 25.0),
    new DataPoint(20.0, 30.0),
    new DataPoint(30.0, 28.0),
    new DataPoint(40.0, 32.0),
    new DataPoint(50.0, 35.0),
    new DataPoint(60.0, 33.0),
], '#FF5733');

$series2 = new DataSeries('Humidity', [
    new DataPoint(0.0, 60.0),
    new DataPoint(10.0, 55.0),
    new DataPoint(20.0, 50.0),
    new DataPoint(30.0, 52.0),
    new DataPoint(40.0, 48.0),
    new DataPoint(50.0, 45.0),
    new DataPoint(60.0, 47.0),
], '#33C1FF');

$chart->setSize(900, 600)
      ->setFormat(ImageFormat::SVG)
      ->setBackgroundColor('#FFFFFF')
      ->setAxisColor('#333333')
      ->enableGrid()
      ->setGridColor('#E0E0E0')
      ->addDataSeries($series1)
      ->addDataSeries($series2);

// Generate with auto grid spacing
$outputPath = $outputDir . '/grid_lines.svg';
$chart->generate($outputPath);

echo "Grid lines chart generated: {$outputPath}\n";

// Create a bar chart with custom grid spacing
$chart2 = new Chart(ChartType::Bar);

$sales = new DataSeries('Q1 Sales', [
    new DataPoint(1.0, 120.0),
    new DataPoint(2.0, 150.0),
    new DataPoint(3.0, 180.0),
    new DataPoint(4.0, 165.0),
], '#2ECC71');

$chart2->setSize(800, 600)
       ->setFormat(ImageFormat::SVG)
       ->setBackgroundColor('#F8F9FA')
       ->enableGrid()
       ->setGridColor('#CCCCCC')
       ->setGridSpacing(25.0)
       ->addDataSeries($sales);

$outputPath2 = $outputDir . '/bar_with_grid.svg';
$chart2->generate($outputPath2);

echo "Bar chart with grid generated: {$outputPath2}\n";

echo "\nGrid lines examples generated successfully!\n";
