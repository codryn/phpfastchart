<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Renderer;

use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\AxisConfiguration;
use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use Codryn\PHPFastChart\Configuration\GridConfiguration;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Configuration\LegendConfiguration;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use Codryn\PHPFastChart\Renderer\RasterRenderer;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for RasterRenderer (GD-based PNG/WEBP renderer).
 */
final class RasterRendererTest extends TestCase
{
    public function testConstructorCreatesImageSuccessfully(): void
    {
        $renderer = new RasterRenderer(800, 600, ImageFormat::PNG);
        $this->assertInstanceOf(RasterRenderer::class, $renderer);
    }

    public function testConstructorThrowsForInvalidWidth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Width and height must be at least 1 pixel');
        new RasterRenderer(0, 600, ImageFormat::PNG);
    }

    public function testRenderLineChartProducesPngData(): void
    {
        $renderer = new RasterRenderer(400, 300, ImageFormat::PNG);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(1.0, 20.0),
            new DataPoint(2.0, 15.0),
        ]);

        $output = $renderer->render(
            ChartType::Line,
            [$series],
            new ColorConfiguration(),
            new GridConfiguration(),
            new AxisConfiguration(),
            new LegendConfiguration()
        );

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith("\x89PNG", $output);
    }

    public function testRenderBarChartProducesPngData(): void
    {
        $renderer = new RasterRenderer(400, 300, ImageFormat::PNG);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(1.0, 20.0),
        ]);

        $output = $renderer->render(
            ChartType::Bar,
            [$series],
            new ColorConfiguration(),
            new GridConfiguration(),
            new AxisConfiguration(),
            new LegendConfiguration()
        );

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith("\x89PNG", $output);
    }

    public function testRenderScatterChartProducesPngData(): void
    {
        $renderer = new RasterRenderer(400, 300, ImageFormat::PNG);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(1.0, 20.0),
        ]);

        $output = $renderer->render(
            ChartType::Scatter,
            [$series],
            new ColorConfiguration(),
            new GridConfiguration(),
            new AxisConfiguration(),
            new LegendConfiguration()
        );

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith("\x89PNG", $output);
    }

    public function testRenderWithWebpFormatProducesWebpData(): void
    {
        $renderer = new RasterRenderer(400, 300, ImageFormat::WEBP);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(1.0, 20.0),
        ]);

        $output = $renderer->render(
            ChartType::Line,
            [$series],
            new ColorConfiguration(),
            new GridConfiguration(),
            new AxisConfiguration(),
            new LegendConfiguration()
        );

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('RIFF', $output);
        $this->assertStringContainsString('WEBP', $output);
    }

    public function testRenderWithColorConfiguration(): void
    {
        $renderer = new RasterRenderer(400, 300, ImageFormat::PNG);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(1.0, 20.0),
        ]);
        $colors = new ColorConfiguration(
            backgroundColor: '#ffffff',
            axisColor: '#000000'
        );

        $output = $renderer->render(
            ChartType::Line,
            [$series],
            $colors,
            new GridConfiguration(),
            new AxisConfiguration(),
            new LegendConfiguration()
        );

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith("\x89PNG", $output);
    }
}
