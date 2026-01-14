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
 * Integration tests for Pie chart generation.
 *
 * @covers \Codryn\PHPFastChart\Chart\Chart
 * @covers \Codryn\PHPFastChart\Renderer\SvgRenderer
 */
final class PieChartTest extends TestCase
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

    public function testGenerateBasicPieChartAsSvg(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(800, 600);
        $chart->setFormat(ImageFormat::SVG);

        $series = new DataSeries('Sales', [
            new DataPoint(0.0, 100.0, 'Q1'),
            new DataPoint(1.0, 150.0, 'Q2'),
            new DataPoint(2.0, 120.0, 'Q3'),
            new DataPoint(3.0, 180.0, 'Q4'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/pie_basic.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('</svg>', $content);
    }

    public function testPieChartWithCustomColors(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(800, 600);
        $chart->setFormat(ImageFormat::SVG);
        $chart->setBackgroundColor('#ffffff');

        $series = new DataSeries('Market Share', [
            new DataPoint(0.0, 30.0, 'Product A'),
            new DataPoint(1.0, 50.0, 'Product B'),
            new DataPoint(2.0, 20.0, 'Product C'),
        ], '#FF6384');

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/pie_colors.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('rgb(255,255,255)', $content);
    }

    public function testPieChartWithSingleSlice(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(600, 600);
        $chart->setFormat(ImageFormat::SVG);

        $series = new DataSeries('Single', [
            new DataPoint(0.0, 100.0, 'Only'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/pie_single.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
    }

    public function testPieChartWithTitle(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(800, 600);
        $chart->setFormat(ImageFormat::SVG);
        $chart->setTitle('Sales Distribution');

        $series = new DataSeries('Sales', [
            new DataPoint(0.0, 200.0, 'Region A'),
            new DataPoint(1.0, 300.0, 'Region B'),
            new DataPoint(2.0, 150.0, 'Region C'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/pie_title.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('Sales Distribution', $content);
    }

    public function testPieChartWithLegend(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(900, 600);
        $chart->setFormat(ImageFormat::SVG);
        $chart->enableLegend();

        $series = new DataSeries('Categories', [
            new DataPoint(0.0, 100.0, 'Category 1'),
            new DataPoint(1.0, 200.0, 'Category 2'),
            new DataPoint(2.0, 150.0, 'Category 3'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/pie_legend.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
    }

    public function testPieChartRendersSlicesSvg(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setFormat(ImageFormat::SVG);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 25.0, 'A'),
            new DataPoint(1.0, 25.0, 'B'),
            new DataPoint(2.0, 25.0, 'C'),
            new DataPoint(3.0, 25.0, 'D'),
        ]);

        $chart->addDataSeries($series);

        $svg = $chart->render();

        // Should contain path elements for slices
        $this->assertStringContainsString('<path', $svg);
        $this->assertStringContainsString('d=', $svg);
    }

    public function testPieChartAsPng(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(600, 600);
        $chart->setFormat(ImageFormat::PNG);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 40.0, 'A'),
            new DataPoint(1.0, 30.0, 'B'),
            new DataPoint(2.0, 20.0, 'C'),
            new DataPoint(3.0, 10.0, 'D'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/pie_png.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        // Verify it's a valid PNG
        $info = getimagesize($outputPath);
        $this->assertNotFalse($info);
        $this->assertSame(IMAGETYPE_PNG, $info[2]);
    }

    public function testPieChartAsWebp(): void
    {
        $chart = new Chart(ChartType::Pie);
        $chart->setSize(600, 600);
        $chart->setFormat(ImageFormat::WEBP);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 25.0, 'Q1'),
            new DataPoint(1.0, 35.0, 'Q2'),
            new DataPoint(2.0, 20.0, 'Q3'),
            new DataPoint(3.0, 20.0, 'Q4'),
        ]);

        $chart->addDataSeries($series);

        $outputPath = $this->outputDir . '/pie_webp.webp';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        // Verify it's a valid WEBP
        $info = getimagesize($outputPath);
        $this->assertNotFalse($info);
        $this->assertSame(IMAGETYPE_WEBP, $info[2]);
    }
}
