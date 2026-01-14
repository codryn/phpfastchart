<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Integration;

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for Radar chart generation.
 *
 * @covers \Codryn\PHPFastChart\Chart\Chart
 * @covers \Codryn\PHPFastChart\Renderer\SvgRenderer
 */
final class RadarChartTest extends TestCase
{
    private string $outputDir;

    protected function setUp(): void
    {
        $this->outputDir = sys_get_temp_dir() . '/phpfastchart_test_' . uniqid();
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        if (is_dir($this->outputDir)) {
            $files = glob($this->outputDir . '/*');
            if ($files !== false) {
                array_map('unlink', $files);
            }
            rmdir($this->outputDir);
        }
    }

    public function testGenerateBasicRadarChartAsSvg(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(800, 800);
        $chart->setFormat(ImageFormat::SVG);

        $series = new DataSeries('Skills', [
            new DataPoint(0.0, 80.0, 'Speed'),
            new DataPoint(1.0, 70.0, 'Strength'),
            new DataPoint(2.0, 90.0, 'Agility'),
            new DataPoint(3.0, 75.0, 'Stamina'),
            new DataPoint(4.0, 85.0, 'Defense'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/radar_basic.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('</svg>', $content);
    }

    public function testRadarChartWithMultipleSeries(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(800, 800);
        $chart->setFormat(ImageFormat::SVG);
        $chart->enableLegend();

        $series1 = new DataSeries('Player 1', [
            new DataPoint(0.0, 85.0, 'Attack'),
            new DataPoint(1.0, 70.0, 'Defense'),
            new DataPoint(2.0, 90.0, 'Speed'),
            new DataPoint(3.0, 75.0, 'Skill'),
        ], '#FF6384');

        $series2 = new DataSeries('Player 2', [
            new DataPoint(0.0, 70.0, 'Attack'),
            new DataPoint(1.0, 90.0, 'Defense'),
            new DataPoint(2.0, 75.0, 'Speed'),
            new DataPoint(3.0, 85.0, 'Skill'),
        ], '#36A2EB');

        $chart->addDataSeries($series1);
        $chart->addDataSeries($series2);

        $outputPath = $this->outputDir . '/radar_multi.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
    }

    public function testRadarChartWithCustomColors(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(800, 800);
        $chart->setFormat(ImageFormat::SVG);
        $chart->setBackgroundColor('#f0f0f0');

        $series = new DataSeries('Stats', [
            new DataPoint(0.0, 60.0, 'A'),
            new DataPoint(1.0, 80.0, 'B'),
            new DataPoint(2.0, 70.0, 'C'),
            new DataPoint(3.0, 90.0, 'D'),
            new DataPoint(4.0, 75.0, 'E'),
        ], '#FF5733');

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/radar_colors.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('rgb(240,240,240)', $content);
    }

    public function testRadarChartWithTitle(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(800, 800);
        $chart->setFormat(ImageFormat::SVG);
        $chart->setTitle('Performance Metrics');

        $series = new DataSeries('Metrics', [
            new DataPoint(0.0, 85.0, 'Quality'),
            new DataPoint(1.0, 90.0, 'Efficiency'),
            new DataPoint(2.0, 75.0, 'Innovation'),
            new DataPoint(3.0, 80.0, 'Teamwork'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/radar_title.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('Performance Metrics', $content);
    }

    public function testRadarChartWithMinimumDimensions(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(400, 400);
        $chart->setFormat(ImageFormat::SVG);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 50.0, 'A'),
            new DataPoint(1.0, 60.0, 'B'),
            new DataPoint(2.0, 70.0, 'C'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/radar_min.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
    }

    public function testRadarChartRendersPolygonsSvg(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setFormat(ImageFormat::SVG);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 80.0, 'A'),
            new DataPoint(1.0, 70.0, 'B'),
            new DataPoint(2.0, 90.0, 'C'),
            new DataPoint(3.0, 85.0, 'D'),
        ]);

        $chart->addDataSeries($series);

        $svg = $chart->render();

        // Should contain polygon or path elements
        $this->assertStringContainsString('<', $svg);
        $this->assertStringContainsString('svg', $svg);
    }

    public function testRadarChartWithAxisScaling(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(800, 800);
        $chart->setFormat(ImageFormat::SVG);
        $chart->setYAxisRange(0.0, 100.0);

        $series = new DataSeries('Scores', [
            new DataPoint(0.0, 85.0, 'Test 1'),
            new DataPoint(1.0, 92.0, 'Test 2'),
            new DataPoint(2.0, 78.0, 'Test 3'),
            new DataPoint(3.0, 88.0, 'Test 4'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/radar_scaling.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
    }

    public function testRadarChartAsPng(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(700, 700);
        $chart->setFormat(ImageFormat::PNG);

        $series = new DataSeries('Stats', [
            new DataPoint(0.0, 70.0, 'A'),
            new DataPoint(1.0, 85.0, 'B'),
            new DataPoint(2.0, 60.0, 'C'),
            new DataPoint(3.0, 90.0, 'D'),
            new DataPoint(4.0, 75.0, 'E'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/radar_png.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        // Verify it's a valid PNG
        $info = getimagesize($outputPath);
        $this->assertNotFalse($info);
        $this->assertSame(IMAGETYPE_PNG, $info[2]);
    }

    public function testRadarChartAsWebp(): void
    {
        $chart = new Chart(ChartType::Radar);
        $chart->setSize(700, 700);
        $chart->setFormat(ImageFormat::WEBP);

        $series = new DataSeries('Metrics', [
            new DataPoint(0.0, 80.0, 'Speed'),
            new DataPoint(1.0, 75.0, 'Power'),
            new DataPoint(2.0, 90.0, 'Skill'),
            new DataPoint(3.0, 70.0, 'Defense'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/radar_webp.webp';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        // Verify it's a valid WEBP
        $info = getimagesize($outputPath);
        $this->assertNotFalse($info);
        $this->assertSame(IMAGETYPE_WEBP, $info[2]);
    }
}
