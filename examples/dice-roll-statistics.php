<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Data\StatisticalOverlay;

// Create output directory
$outputDir = __DIR__ . '/output';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

echo "🎲 Dice Roll Statistics Example\n";
echo "================================\n\n";

// Simulate rolling 3d6 (three six-sided dice) 1000 times
echo "Rolling 3d6 1000 times...\n";
$rolls = [];
$rollCounts = [];

for ($i = 0; $i < 1000; $i++) {
    $roll = rand(1, 6) + rand(1, 6) + rand(1, 6);
    $rolls[] = $roll;
    
    if (!isset($rollCounts[$roll])) {
        $rollCounts[$roll] = 0;
    }
    $rollCounts[$roll]++;
}

// Calculate statistics from actual rolls
$min = min($rolls);
$max = max($rolls);
$average = array_sum($rolls) / count($rolls);

// Calculate standard deviation
$variance = 0;
foreach ($rolls as $roll) {
    $variance += pow($roll - $average, 2);
}
$stdDeviation = sqrt($variance / count($rolls));

echo "📊 Roll Statistics:\n";
echo sprintf("  Min: %d\n", $min);
echo sprintf("  Max: %d\n", $max);
echo sprintf("  Average: %.2f\n", $average);
echo sprintf("  Std Deviation: %.2f\n\n", $stdDeviation);

// Theoretical statistics for 3d6
// min = 3, max = 18, average = 10.5, std dev ≈ 2.96
$theoreticalMin = 3.0;
$theoreticalMax = 18.0;
$theoreticalAvg = 10.5;
$theoreticalStdDev = 2.96;

echo "📈 Theoretical Statistics (3d6):\n";
echo sprintf("  Min: %d\n", (int)$theoreticalMin);
echo sprintf("  Max: %d\n", (int)$theoreticalMax);
echo sprintf("  Average: %.2f\n", $theoreticalAvg);
echo sprintf("  Std Deviation: %.2f\n\n", $theoreticalStdDev);

// Create data series from roll counts
$dataPoints = [];
for ($i = 3; $i <= 18; $i++) {
    $count = $rollCounts[$i] ?? 0;
    $dataPoints[] = new DataPoint((float)$i, (float)$count, (string)$i);
}

$rollData = new DataSeries('Roll Frequency', $dataPoints, '#3498db');

// Create statistical overlays
$actualOverlay = new StatisticalOverlay(
    min: $min,
    max: $max,
    average: $average,
    stdDeviation: $stdDeviation,
    color: '#e74c3c' // Red for actual statistics
);

$theoreticalOverlay = new StatisticalOverlay(
    min: $theoreticalMin,
    max: $theoreticalMax,
    average: $theoreticalAvg,
    stdDeviation: $theoreticalStdDev,
    color: '#2ecc71' // Green for theoretical statistics
);

// Create bar chart with overlays
$chart = new Chart(ChartType::Bar);
$chart->setSize(1200, 800)
      ->setFormat(ImageFormat::SVG)
      ->setBackgroundColor('#FFFFFF')
      ->setAxisColor('#333333')
      ->addDataSeries($rollData)
      ->setTitle('3d6 Dice Roll Statistics (1000 rolls)')
      ->setXAxisLabel('Roll Value')
      ->setYAxisLabel('Frequency')
      ->enableGrid(true)
      ->setGridColor('#E0E0E0')
      ->addStatisticalOverlay($actualOverlay)   // Red overlay: actual from rolls
      ->addStatisticalOverlay($theoreticalOverlay); // Green overlay: theoretical

// Generate SVG
$svgPath = $outputDir . '/dice_roll_statistics.svg';
$chart->generate($svgPath);
echo "✅ Chart with statistical overlays generated!\n";
echo "📊 Output: {$svgPath}\n";
echo "\nThe chart shows:\n";
echo "  - Blue bars: Roll frequency distribution\n";
echo "  - Red lines: Actual statistics from the rolls\n";
echo "  - Green lines: Theoretical 3d6 statistics\n";

// Also generate PNG version if GD is available
if (extension_loaded('gd')) {
    $chart->setFormat(ImageFormat::PNG);
    $pngPath = $outputDir . '/dice_roll_statistics.png';
    $chart->generate($pngPath);
    echo "\n✅ PNG version also generated!\n";
    echo "📊 Output: {$pngPath}\n";
}
