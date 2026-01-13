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
 * Integration test for chart generation.
 */
final class ChartGenerationTest extends TestCase
{
    private string $outputDir;

    protected function setUp(): void
    {
        $this->outputDir = sys_get_temp_dir() . '/phpfastchart_test_' . uniqid();
        mkdir($this->outputDir, 0777, true);
    }

    protected function tearDown(): void
    {
        // Clean up test files
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

    public function testGenerateSvgLineChart(): void
    {
        $chart = new Chart(ChartType::Line);
        
        $series = new DataSeries('Sales', [
            new DataPoint(1.0, 100.0),
            new DataPoint(2.0, 150.0),
            new DataPoint(3.0, 120.0),
            new DataPoint(4.0, 180.0),
        ]);
        
        $chart->setSize(800, 600)
              ->setFormat(ImageFormat::SVG)
              ->setBackgroundColor('#FFFFFF')
              ->addDataSeries($series);
        
        $outputPath = $this->outputDir . '/test_line.svg';
        $chart->generate($outputPath);
        
        $this->assertFileExists($outputPath);
        
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('</svg>', $content);
        $this->assertStringContainsString('<path', $content);
    }

    public function testRenderSvgLineChart(): void
    {
        $chart = new Chart(ChartType::Line);
        
        $series = new DataSeries('Revenue', [
            new DataPoint(1.0, 50.0),
            new DataPoint(2.0, 75.0),
        ], '#FF0000');
        
        $chart->addDataSeries($series);
        
        $content = $chart->render();
        
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('</svg>', $content);
        $this->assertStringContainsString('width="800"', $content);
        $this->assertStringContainsString('height="600"', $content);
    }

    public function testGenerateSvgBarChart(): void
    {
        $chart = new Chart(ChartType::Bar);

        $series = new DataSeries('Products', [
            new DataPoint(1.0, 50.0),
            new DataPoint(2.0, 80.0),
            new DataPoint(3.0, 60.0),
        ], '#e74c3c');

        $chart->setSize(800, 600)
              ->addDataSeries($series);

        $outputPath = $this->outputDir . '/test_bar.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);

        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('<rect', $content);
    }

    public function testCustomColors(): void
    {
        $chart = new Chart(ChartType::Line);

        $series = new DataSeries('Data', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ]);

        $chart->setBackgroundColor('#f0f0f0')
              ->setAxisColor('#FF0000')
              ->addDataSeries($series);

        $content = $chart->render();

        $this->assertStringContainsString('fill="rgb(240,240,240)"', $content);
        $this->assertStringContainsString('stroke="rgb(255,0,0)"', $content);
    }
}
