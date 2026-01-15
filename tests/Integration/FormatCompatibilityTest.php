<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Integration;

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Tests\Fixtures\SampleData;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for format compatibility across all chart types.
 *
 * Tests all 5 chart types × 3 formats = 15 combinations
 */
final class FormatCompatibilityTest extends TestCase
{
    private string $outputDir;

    protected function setUp(): void
    {
        $this->outputDir = sys_get_temp_dir() . '/phpfastchart-test-' . uniqid();
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        if (is_dir($this->outputDir)) {
            $files = glob($this->outputDir . '/*');
            if ($files !== false) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
            rmdir($this->outputDir);
        }
    }

    /**
     * Test Line chart with PNG format.
     */
    public function testLineChartPng(): void
    {
        $chart = new Chart(ChartType::Line);
        $chart->setSize(800, 600)->setFormat(ImageFormat::PNG);
        foreach (SampleData::multiLineSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/line-chart.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));

        // Verify PNG signature
        $handle = fopen($outputPath, 'rb');
        $this->assertNotFalse($handle);
        $signature = fread($handle, 8);
        fclose($handle);
        $this->assertSame("\x89PNG\r\n\x1a\n", $signature);
    }

    /**
     * Test Line chart with WEBP format.
     */
    public function testLineChartWebp(): void
    {
        $chart = new Chart(ChartType::Line);
        $chart->setSize(800, 600)->setFormat(ImageFormat::WEBP);
        foreach (SampleData::multiLineSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/line-chart.webp';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));

        // Verify WEBP signature (RIFF...WEBP)
        $handle = fopen($outputPath, 'rb');
        $this->assertNotFalse($handle);
        $signature = fread($handle, 4);
        fclose($handle);
        $this->assertSame('RIFF', $signature);
    }

    /**
     * Test Line chart with SVG format.
     */
    public function testLineChartSvg(): void
    {
        $chart = new Chart(ChartType::Line);
        $chart->setSize(800, 600)->setFormat(ImageFormat::SVG);
        foreach (SampleData::multiLineSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/line-chart.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertIsString($content);
        $this->assertStringContainsString('<?xml', $content);
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('</svg>', $content);
    }

    /**
     * Test Bar chart with PNG format.
     */
    public function testBarChartPng(): void
    {
        $chart = new Chart(ChartType::Bar);
        $chart->setSize(800, 600)->setFormat(ImageFormat::PNG);
        foreach (SampleData::multiBarSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/bar-chart.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    /**
     * Test Bar chart with WEBP format.
     */
    public function testBarChartWebp(): void
    {
        $chart = new Chart(ChartType::Bar);
        $chart->setSize(800, 600)->setFormat(ImageFormat::WEBP);
        foreach (SampleData::multiBarSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/bar-chart.webp';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    /**
     * Test Bar chart with SVG format.
     */
    public function testBarChartSvg(): void
    {
        $chart = new Chart(ChartType::Bar);
        $chart->setSize(800, 600)->setFormat(ImageFormat::SVG);
        foreach (SampleData::multiBarSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/bar-chart.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertIsString($content);
        $this->assertStringContainsString('<svg', $content);
    }

    /**
     * Test Scatter chart with PNG format.
     */
    public function testScatterChartPng(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $chart->setSize(800, 600)->setFormat(ImageFormat::PNG);
        foreach (SampleData::scatterSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/scatter-chart.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    /**
     * Test Scatter chart with WEBP format.
     */
    public function testScatterChartWebp(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $chart->setSize(800, 600)->setFormat(ImageFormat::WEBP);
        foreach (SampleData::scatterSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/scatter-chart.webp';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    /**
     * Test Scatter chart with SVG format.
     */
    public function testScatterChartSvg(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $chart->setSize(800, 600)->setFormat(ImageFormat::SVG);
        foreach (SampleData::scatterSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/scatter-chart.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertIsString($content);
        $this->assertStringContainsString('<svg', $content);
    }

    /**
     * Test Pie chart with PNG format.
     */
    public function testPieChartPng(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(800, 600)->setFormat(ImageFormat::PNG);
        foreach (SampleData::pieSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/pie-chart.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    /**
     * Test Pie chart with WEBP format.
     */
    public function testPieChartWebp(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(800, 600)->setFormat(ImageFormat::WEBP);
        foreach (SampleData::pieSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/pie-chart.webp';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    /**
     * Test Pie chart with SVG format.
     */
    public function testPieChartSvg(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(800, 600)->setFormat(ImageFormat::SVG);
        foreach (SampleData::pieSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/pie-chart.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertIsString($content);
        $this->assertStringContainsString('<svg', $content);
    }

    /**
     * Test Radar chart with PNG format.
     */
    public function testRadarChartPng(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(800, 600)->setFormat(ImageFormat::PNG);
        foreach (SampleData::radarSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/radar-chart.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    /**
     * Test Radar chart with WEBP format.
     */
    public function testRadarChartWebp(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(800, 600)->setFormat(ImageFormat::WEBP);
        foreach (SampleData::radarSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/radar-chart.webp';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    /**
     * Test Radar chart with SVG format.
     */
    public function testRadarChartSvg(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(800, 600)->setFormat(ImageFormat::SVG);
        foreach (SampleData::radarSeries() as $series) {
            $chart->addDataSeries($series);
        }

        $outputPath = $this->outputDir . '/radar-chart.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertIsString($content);
        $this->assertStringContainsString('<svg', $content);
    }
}
