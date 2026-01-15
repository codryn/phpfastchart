<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Fixtures;

use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;

/**
 * Sample data provider for consistent testing across the test suite.
 *
 * Provides pre-configured datasets for all chart types with various
 * complexity levels (simple, medium, complex) for comprehensive testing.
 */
final class SampleData
{
    /**
     * Get simple line chart data (single series, few points).
     *
     * @return array<DataSeries>
     */
    public static function simpleLineSeries(): array
    {
        return [
            new DataSeries(
                'Sales',
                [
                    new DataPoint(0, 10, 'Jan'),
                    new DataPoint(1, 20, 'Feb'),
                    new DataPoint(2, 15, 'Mar'),
                    new DataPoint(3, 30, 'Apr'),
                ],
                '#0066CC'
            ),
        ];
    }

    /**
     * Get multi-series line chart data.
     *
     * @return array<DataSeries>
     */
    public static function multiLineSeries(): array
    {
        return [
            new DataSeries(
                'Product A',
                [
                    new DataPoint(0, 10, 'Q1'),
                    new DataPoint(1, 20, 'Q2'),
                    new DataPoint(2, 15, 'Q3'),
                    new DataPoint(3, 30, 'Q4'),
                ],
                '#0066CC'
            ),
            new DataSeries(
                'Product B',
                [
                    new DataPoint(0, 15, 'Q1'),
                    new DataPoint(1, 18, 'Q2'),
                    new DataPoint(2, 22, 'Q3'),
                    new DataPoint(3, 28, 'Q4'),
                ],
                '#FF6600'
            ),
            new DataSeries(
                'Product C',
                [
                    new DataPoint(0, 8, 'Q1'),
                    new DataPoint(1, 12, 'Q2'),
                    new DataPoint(2, 18, 'Q3'),
                    new DataPoint(3, 25, 'Q4'),
                ],
                '#00AA44'
            ),
        ];
    }

    /**
     * Get bar chart data.
     *
     * @return array<DataSeries>
     */
    public static function barSeries(): array
    {
        return [
            new DataSeries(
                'Revenue',
                [
                    new DataPoint(0, 50, '2020'),
                    new DataPoint(1, 75, '2021'),
                    new DataPoint(2, 100, '2022'),
                    new DataPoint(3, 125, '2023'),
                    new DataPoint(4, 150, '2024'),
                ],
                '#3366CC'
            ),
        ];
    }

    /**
     * Get multi-series bar chart data.
     *
     * @return array<DataSeries>
     */
    public static function multiBarSeries(): array
    {
        return [
            new DataSeries(
                'Region A',
                [
                    new DataPoint(0, 50, 'Q1'),
                    new DataPoint(1, 75, 'Q2'),
                    new DataPoint(2, 60, 'Q3'),
                    new DataPoint(3, 90, 'Q4'),
                ],
                '#0066CC'
            ),
            new DataSeries(
                'Region B',
                [
                    new DataPoint(0, 40, 'Q1'),
                    new DataPoint(1, 65, 'Q2'),
                    new DataPoint(2, 80, 'Q3'),
                    new DataPoint(3, 95, 'Q4'),
                ],
                '#FF6600'
            ),
        ];
    }

    /**
     * Get scatter plot data.
     *
     * @return array<DataSeries>
     */
    public static function scatterSeries(): array
    {
        return [
            new DataSeries(
                'Dataset A',
                [
                    new DataPoint(1.5, 3.2),
                    new DataPoint(2.8, 5.1),
                    new DataPoint(4.2, 4.8),
                    new DataPoint(5.5, 6.9),
                    new DataPoint(7.1, 8.3),
                ],
                '#0066CC'
            ),
            new DataSeries(
                'Dataset B',
                [
                    new DataPoint(2.0, 2.5),
                    new DataPoint(3.5, 4.2),
                    new DataPoint(5.0, 5.8),
                    new DataPoint(6.5, 7.1),
                    new DataPoint(8.0, 8.9),
                ],
                '#FF6600'
            ),
        ];
    }

    /**
     * Get pie chart data (single series only).
     *
     * @return array<DataSeries>
     */
    public static function pieSeries(): array
    {
        return [
            new DataSeries(
                'Market Share',
                [
                    new DataPoint(0, 35, 'Product A'),
                    new DataPoint(1, 25, 'Product B'),
                    new DataPoint(2, 20, 'Product C'),
                    new DataPoint(3, 15, 'Product D'),
                    new DataPoint(4, 5, 'Others'),
                ],
                '#0066CC' // Base color, slices auto-colored
            ),
        ];
    }

    /**
     * Get radar chart data.
     *
     * @return array<DataSeries>
     */
    public static function radarSeries(): array
    {
        return [
            new DataSeries(
                'Player 1',
                [
                    new DataPoint(0, 8, 'Speed'),
                    new DataPoint(1, 7, 'Strength'),
                    new DataPoint(2, 9, 'Agility'),
                    new DataPoint(3, 6, 'Defense'),
                    new DataPoint(4, 8, 'Stamina'),
                ],
                '#0066CC'
            ),
            new DataSeries(
                'Player 2',
                [
                    new DataPoint(0, 6, 'Speed'),
                    new DataPoint(1, 9, 'Strength'),
                    new DataPoint(2, 7, 'Agility'),
                    new DataPoint(3, 8, 'Defense'),
                    new DataPoint(4, 7, 'Stamina'),
                ],
                '#FF6600'
            ),
        ];
    }

    /**
     * Get large dataset (1000 points) for performance testing.
     *
     * @return array<DataSeries>
     */
    public static function largeDataset(): array
    {
        $points = [];
        for ($i = 0; $i < 1000; $i++) {
            $points[] = new DataPoint(
                $i,
                50 + 30 * sin($i / 10) + rand(-5, 5)
            );
        }

        return [
            new DataSeries('Large Dataset', $points, '#0066CC'),
        ];
    }

    /**
     * Get data with negative values.
     *
     * @return array<DataSeries>
     */
    public static function negativeValuesSeries(): array
    {
        return [
            new DataSeries(
                'Profit/Loss',
                [
                    new DataPoint(0, -10, 'Jan'),
                    new DataPoint(1, -5, 'Feb'),
                    new DataPoint(2, 5, 'Mar'),
                    new DataPoint(3, 15, 'Apr'),
                    new DataPoint(4, 20, 'May'),
                    new DataPoint(5, 10, 'Jun'),
                    new DataPoint(6, -2, 'Jul'),
                ],
                '#0066CC'
            ),
        ];
    }

    /**
     * Get data with very small values (decimal precision).
     *
     * @return array<DataSeries>
     */
    public static function smallValuesSeries(): array
    {
        return [
            new DataSeries(
                'Precision Data',
                [
                    new DataPoint(0, 0.001, 'A'),
                    new DataPoint(1, 0.005, 'B'),
                    new DataPoint(2, 0.003, 'C'),
                    new DataPoint(3, 0.008, 'D'),
                ],
                '#0066CC'
            ),
        ];
    }

    /**
     * Get data with very large values.
     *
     * @return array<DataSeries>
     */
    public static function largeValuesSeries(): array
    {
        return [
            new DataSeries(
                'Population',
                [
                    new DataPoint(0, 1000000, 'City A'),
                    new DataPoint(1, 2500000, 'City B'),
                    new DataPoint(2, 1800000, 'City C'),
                    new DataPoint(3, 3200000, 'City D'),
                ],
                '#0066CC'
            ),
        ];
    }

    /**
     * Get data with zero values.
     *
     * @return array<DataSeries>
     */
    public static function zeroValuesSeries(): array
    {
        return [
            new DataSeries(
                'Sales',
                [
                    new DataPoint(0, 10, 'Jan'),
                    new DataPoint(1, 0, 'Feb'),
                    new DataPoint(2, 15, 'Mar'),
                    new DataPoint(3, 0, 'Apr'),
                    new DataPoint(4, 20, 'May'),
                ],
                '#0066CC'
            ),
        ];
    }

    /**
     * Get data with single point (edge case).
     *
     * @return array<DataSeries>
     */
    public static function singlePointSeries(): array
    {
        return [
            new DataSeries(
                'Single',
                [new DataPoint(0, 50, 'Point')],
                '#0066CC'
            ),
        ];
    }

    /**
     * Get color palette for testing.
     *
     * @return array<string>
     */
    public static function colorPalette(): array
    {
        return [
            '#0066CC',
            '#FF6600',
            '#00AA44',
            '#CC0066',
            '#6600CC',
            '#00CCAA',
            '#CCAA00',
            '#CC6600',
        ];
    }
}
