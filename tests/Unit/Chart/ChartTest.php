<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Chart;

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Chart construction and validation.
 */
final class ChartTest extends TestCase
{
    public function testConstructorCreatesChartWithDefaultValues(): void
    {
        $chart = new Chart(ChartType::Line);

        $this->assertInstanceOf(Chart::class, $chart);
    }

    public function testConstructorAcceptsAllChartTypes(): void
    {
        $types = [
            ChartType::Line,
            ChartType::Bar,
            ChartType::Scatter,
            ChartType::Pie,
            ChartType::Radar,
        ];

        foreach ($types as $type) {
            $chart = new Chart($type);
            $this->assertInstanceOf(Chart::class, $chart);
        }
    }

    public function testSetSizeWithValidDimensions(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setSize(1000, 800);

        $this->assertSame($chart, $result, 'setSize should return self for fluent interface');
    }

    public function testSetSizeWithMinimumDimensions(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setSize(50, 50);

        $this->assertSame($chart, $result);
    }

    public function testSetSizeWithMaximumDimensions(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setSize(4000, 4000);

        $this->assertSame($chart, $result);
    }

    public function testSetSizeThrowsForWidthTooSmall(): void
    {
        $chart = new Chart(ChartType::Line);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Width must be between 50 and 4000, got 49');

        $chart->setSize(49, 600);
    }

    public function testSetSizeThrowsForWidthTooLarge(): void
    {
        $chart = new Chart(ChartType::Line);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Width must be between 50 and 4000, got 4001');

        $chart->setSize(4001, 600);
    }

    public function testSetSizeThrowsForHeightTooSmall(): void
    {
        $chart = new Chart(ChartType::Line);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Height must be between 50 and 4000, got 49');

        $chart->setSize(800, 49);
    }

    public function testSetSizeThrowsForHeightTooLarge(): void
    {
        $chart = new Chart(ChartType::Line);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Height must be between 50 and 4000, got 4001');

        $chart->setSize(800, 4001);
    }

    public function testSetSizeThrowsForNegativeWidth(): void
    {
        $chart = new Chart(ChartType::Line);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Width must be between 50 and 4000, got -100');

        $chart->setSize(-100, 600);
    }

    public function testSetSizeThrowsForZeroHeight(): void
    {
        $chart = new Chart(ChartType::Line);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Height must be between 50 and 4000, got 0');

        $chart->setSize(800, 0);
    }

    public function testSetFormatSvg(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setFormat(ImageFormat::SVG);

        $this->assertSame($chart, $result, 'setFormat should return self for fluent interface');
    }

    public function testSetFormatPng(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setFormat(ImageFormat::PNG);

        $this->assertSame($chart, $result);
    }

    public function testSetFormatWebp(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setFormat(ImageFormat::WEBP);

        $this->assertSame($chart, $result);
    }

    public function testSetBackgroundColorReturnsChartForChaining(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setBackgroundColor('#ffffff');

        $this->assertSame($chart, $result, 'setBackgroundColor should return self for fluent interface');
    }

    public function testSetAxisColorReturnsChartForChaining(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setAxisColor('#000000');

        $this->assertSame($chart, $result, 'setAxisColor should return self for fluent interface');
    }

    public function testAddDataSeriesReturnsChartForChaining(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(1.0, 20.0),
        ]);

        $result = $chart->addDataSeries($series);

        $this->assertSame($chart, $result, 'addDataSeries should return self for fluent interface');
    }

    public function testSetTitleReturnsChartForChaining(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setTitle('Test Chart');

        $this->assertSame($chart, $result, 'setTitle should return self for fluent interface');
    }

    public function testEnableGridReturnsChartForChaining(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->enableGrid();

        $this->assertSame($chart, $result, 'enableGrid should return self for fluent interface');
    }

    public function testSetXAxisRangeReturnsChartForChaining(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setXAxisRange(0.0, 10.0);

        $this->assertSame($chart, $result, 'setXAxisRange should return self for fluent interface');
    }

    public function testSetYAxisRangeReturnsChartForChaining(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->setYAxisRange(0.0, 100.0);

        $this->assertSame($chart, $result, 'setYAxisRange should return self for fluent interface');
    }

    public function testEnableLegendReturnsChartForChaining(): void
    {
        $chart = new Chart(ChartType::Line);
        $result = $chart->enableLegend();

        $this->assertSame($chart, $result, 'enableLegend should return self for fluent interface');
    }

    public function testFluentInterfaceChaining(): void
    {
        $chart = new Chart(ChartType::Bar);
        $series = new DataSeries('Sales', [
            new DataPoint(1.0, 100.0),
            new DataPoint(2.0, 150.0),
            new DataPoint(3.0, 120.0),
        ]);

        $result = $chart
            ->setSize(1200, 800)
            ->setFormat(ImageFormat::PNG)
            ->setBackgroundColor('#ffffff')
            ->setAxisColor('#333333')
            ->addDataSeries($series)
            ->setTitle('Sales Chart')
            ->enableGrid()
            ->enableLegend();

        $this->assertSame($chart, $result, 'All methods should support method chaining');
    }

    public function testMultipleDataSeriesCanBeAdded(): void
    {
        $chart = new Chart(ChartType::Line);

        $series1 = new DataSeries('Series 1', [
            new DataPoint(0.0, 10.0),
            new DataPoint(1.0, 20.0),
        ]);

        $series2 = new DataSeries('Series 2', [
            new DataPoint(0.0, 15.0),
            new DataPoint(1.0, 25.0),
        ]);

        $chart->addDataSeries($series1);
        $chart->addDataSeries($series2);

        // If we got here without exceptions, test succeeds
        $this->expectNotToPerformAssertions();
    }

    public function testChartCanBeConfiguredForDifferentTypes(): void
    {
        $types = [ChartType::Line, ChartType::Bar, ChartType::Scatter];

        foreach ($types as $type) {
            $chart = new Chart($type);
            $chart->setSize(600, 400)
                ->setFormat(ImageFormat::SVG);

            $this->assertInstanceOf(Chart::class, $chart);
        }
    }
}
