<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Create a pie chart
$chart = new Chart(ChartType::Pie);
$chart->setSize(800, 600);
$chart->setFormat(ImageFormat::SVG);
$chart->setBackgroundColor('#ffffff');
$chart->setTitle('Market Share by Product');
$chart->enableDataLabels(); // Show labels on slices instead of legend

// Add data series (pie chart uses first series only, each point is a slice)
// Each point can have its own color specified as the 4th parameter
$series = new DataSeries('Products', [
    new DataPoint(0.0, 350.0, 'Product A', '#FF6384'),  // Red/Pink
    new DataPoint(1.0, 250.0, 'Product B', '#36A2EB'),  // Blue
    new DataPoint(2.0, 180.0, 'Product C', '#FFCE56'),  // Yellow
    new DataPoint(3.0, 120.0, 'Product D', '#4BC0C0'),  // Teal
    new DataPoint(4.0, 100.0, 'Product E', '#9966FF'),  // Purple
]);

$chart->addDataSeries($series);

// Generate SVG file
$outputPath = __DIR__ . '/output/pie-chart.svg';
$chart->generate($outputPath);

echo "Pie chart generated: $outputPath\n";

// Generate PNG version
$chart->setFormat(ImageFormat::PNG);
$outputPath = __DIR__ . '/output/pie-chart.png';
$chart->generate($outputPath);

echo "Pie chart (PNG) generated: $outputPath\n";

// Generate WEBP version
$chart->setFormat(ImageFormat::WEBP);
$outputPath = __DIR__ . '/output/pie-chart.webp';
$chart->generate($outputPath);

echo "Pie chart (WEBP) generated: $outputPath\n";
