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

// Check if GD extension is available
$hasGd = extension_loaded('gd');
if (!$hasGd) {
    echo "ℹ️  GD extension is not loaded. Skipping PNG and WEBP generation.\n";
    echo "   Only SVG format will be generated.\n\n";
}

echo "📊 Generating charts in multiple formats (PNG, WEBP, SVG)...\n\n";

// Example 1: Line chart in multiple formats
$chart = new Chart(ChartType::Line);
$chart->setTitle('Format Comparison Example')
    ->setSize(800, 600);

$series = new DataSeries('Revenue', [
    new DataPoint(1, 50),
    new DataPoint(2, 65),
    new DataPoint(3, 72),
    new DataPoint(4, 85),
], '#3498db');

$chart->addDataSeries($series);

// Generate SVG (always available)
$chart->setFormat(ImageFormat::SVG);
$svgPath = $outputDir . '/format_comparison.svg';
$chart->generate($svgPath);
$svgSize = filesize($svgPath);
echo "✅ SVG generated: {$svgPath} (" . number_format($svgSize) . " bytes)\n";

if ($hasGd) {
    // Generate PNG
    $chart->setFormat(ImageFormat::PNG);
    $pngPath = $outputDir . '/format_comparison.png';
    $chart->generate($pngPath);
    $pngSize = filesize($pngPath);
    echo "✅ PNG generated: {$pngPath} (" . number_format($pngSize) . " bytes)\n";

    // Generate WEBP
    $chart->setFormat(ImageFormat::WEBP);
    $webpPath = $outputDir . '/format_comparison.webp';
    $chart->generate($webpPath);
    $webpSize = filesize($webpPath);
    echo "✅ WEBP generated: {$webpPath} (" . number_format($webpSize) . " bytes)\n\n";

    // Show comparison
    echo "Format Size Comparison:\n";
    echo "  PNG (800x600):  " . number_format($pngSize) . " bytes\n";
    echo "  WEBP (800x600): " . number_format($webpSize) . " bytes\n";
    echo "  SVG (800x600):  " . number_format($svgSize) . " bytes\n\n";

    // Calculate compression ratios
    $webpSaving = round(((1 - $webpSize / $pngSize) * 100));
    echo "💡 WEBP is {$webpSaving}% smaller than PNG\n";
} else {
    echo "⏭️  Skipping PNG generation (GD extension not loaded)\n";
    echo "⏭️  Skipping WEBP generation (GD extension not loaded)\n";
}

echo "\n🎉 Format comparison completed!\n";
echo "📁 Check the {$outputDir}/ directory for output files.\n";
