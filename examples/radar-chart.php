<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

// Create a radar chart
$chart = new Chart(ChartType::Radar);
$chart->setSize(900, 900);
$chart->setFormat(ImageFormat::SVG);
$chart->setBackgroundColor('#f8f9fa');
$chart->setTitle('Player Performance Comparison');
$chart->setYAxisRange(0.0, 100.0);
$chart->enableLegend();

// Add first player data
$series1 = new DataSeries('Player 1', [
    new DataPoint(0.0, 85.0, 'Speed'),
    new DataPoint(1.0, 75.0, 'Strength'),
    new DataPoint(2.0, 90.0, 'Agility'),
    new DataPoint(3.0, 70.0, 'Stamina'),
    new DataPoint(4.0, 80.0, 'Defense'),
    new DataPoint(5.0, 88.0, 'Skill'),
], '#FF6384');

// Add second player data
$series2 = new DataSeries('Player 2', [
    new DataPoint(0.0, 78.0, 'Speed'),
    new DataPoint(1.0, 92.0, 'Strength'),
    new DataPoint(2.0, 75.0, 'Agility'),
    new DataPoint(3.0, 85.0, 'Stamina'),
    new DataPoint(4.0, 88.0, 'Defense'),
    new DataPoint(5.0, 72.0, 'Skill'),
], '#36A2EB');

// Add third player data
$series3 = new DataSeries('Player 3', [
    new DataPoint(0.0, 90.0, 'Speed'),
    new DataPoint(1.0, 68.0, 'Strength'),
    new DataPoint(2.0, 85.0, 'Agility'),
    new DataPoint(3.0, 78.0, 'Stamina'),
    new DataPoint(4.0, 72.0, 'Defense'),
    new DataPoint(5.0, 95.0, 'Skill'),
], '#FFCE56');

$chart->addDataSeries($series1);
$chart->addDataSeries($series2);
$chart->addDataSeries($series3);

// Generate SVG file
$outputPath = __DIR__ . '/output/radar-chart.svg';
$chart->generate($outputPath);

echo "Radar chart generated: $outputPath\n";

// Generate PNG version
$chart->setFormat(ImageFormat::PNG);
$outputPath = __DIR__ . '/output/radar-chart.png';
$chart->generate($outputPath);

echo "Radar chart (PNG) generated: $outputPath\n";

// Generate WEBP version
$chart->setFormat(ImageFormat::WEBP);
$outputPath = __DIR__ . '/output/radar-chart.webp';
$chart->generate($outputPath);

echo "Radar chart (WEBP) generated: $outputPath\n";
