# Data Model: Chart Generation Entities

**Feature**: 2D Chart Generator with Configurable Rendering
**Date**: 2026-01-12
**Phase**: 1 - Architecture & Contracts

## Overview

This document defines all entities, value objects, and their relationships for the chart generation system. All entities follow immutability principles and use strict PHP 8.1+ type declarations.

## Entity Diagram

```
┌─────────────────────┐
│      Chart          │ Main entity, orchestrates rendering
│                     │
│ - type: ChartType   │
│ - width: int        │
│ - height: int       │
│ - format: ImageFormat│
│ - dataCollection    │◆──────┐
│ - configurations    │       │
└─────────────────────┘       │ 1..n
          │                   │
          │ uses              ▼
          │           ┌────────────────┐
          │           │  DataSeries    │
          │           │                │
          │           │ - name: string │
          │           │ - points: []   │◆────┐
          │           │ - lineColor    │     │ 1..n
          │           │ - fillColor    │     │
          │           │ - visible: bool│     │
          │           └────────────────┘     │
          │                                  ▼
          │                          ┌──────────────┐
          │                          │  DataPoint   │
          │                          │              │
          │                          │ - x: mixed   │
          │                          │ - y: ?float  │
          │                          │ - label: ?str│
          ├──────────────────────────└──────────────┘
          │ configured by
          │
          ├──── ImageConfiguration
          ├──── ColorConfiguration  
          ├──── AxisConfiguration (x2: X and Y)
          ├──── GridConfiguration
          └──── LegendConfiguration
```

## Core Entities

### Chart

**Purpose**: Main entry point for chart generation. Orchestrates configuration and rendering.

**Type**: Mutable entity (during configuration), immutable after generation

```php
namespace Codryn\PHPFastChart\Chart;

final class Chart
{
    private ChartType $type;
    private int $width = 800;
    private int $height = 600;
    private ImageFormat $format = ImageFormat::PNG;
    private DataCollection $dataCollection;
    private ColorConfiguration $colors;
    private ?GridConfiguration $grid = null;
    private AxisConfiguration $xAxis;
    private AxisConfiguration $yAxis;
    private ?LegendConfiguration $legend = null;
    
    public function __construct(ChartType $type) {
        $this->type = $type;
        $this->dataCollection = new DataCollection();
        $this->colors = new ColorConfiguration();
        $this->xAxis = new AxisConfiguration('x');
        $this->yAxis = new AxisConfiguration('y');
    }
    
    // Fluent interface methods
    public function setSize(int $width, int $height): self;
    public function setFormat(ImageFormat $format): self;
    public function setBackgroundColor(string $color): self;
    public function addDataSeries(DataSeries $series): self;
    public function setAxisRange(string $axis, ?float $min, ?float $max): self;
    public function enableGrid(bool $horizontal = true, bool $vertical = true): self;
    public function enableLegend(string $position = 'top-right'): self;
    
    // Generation
    public function generate(string $outputPath): void;
    public function render(): string; // Returns image data as string
}
```

**Validation Rules**:
- Width and height: 50-4000 pixels
- At least one DataSeries required
- Output path must be writable directory

**Relationships**:
- Contains 1..n DataSeries (via DataCollection)
- Has exactly 1 of each configuration type
- Uses ChartType enum for type selection
- Delegates rendering to RendererInterface

---

### ChartType

**Purpose**: Enumeration of supported chart types

**Type**: Enum (backed by string in PHP 8.1+)

```php
namespace Codryn\PHPFastChart\Chart;

enum ChartType: string
{
    case Bar = 'bar';
    case Line = 'line';
    case Pie = 'pie';
    case Scatter = 'scatter';
    case Radar = 'radar';
    
    public function getRenderer(): ChartRendererInterface {
        return match($this) {
            self::Bar => new BarChartRenderer(),
            self::Line => new LineChartRenderer(),
            self::Pie => new PieChartRenderer(),
            self::Scatter => new ScatterChartRenderer(),
            self::Radar => new RadarChartRenderer(),
        };
    }
}
```

**Usage**: Type-safe chart type selection, no invalid types possible

---

### ImageFormat

**Purpose**: Enumeration of output image formats

**Type**: Enum (backed by string)

```php
namespace Codryn\PHPFastChart\Configuration;

enum ImageFormat: string
{
    case PNG = 'png';
    case WEBP = 'webp';
    case SVG = 'svg';
    
    public function getExtension(): string {
        return $this->value;
    }
    
    public function getMimeType(): string {
        return match($this) {
            self::PNG => 'image/png',
            self::WEBP => 'image/webp',
            self::SVG => 'image/svg+xml',
        };
    }
    
    public function isRaster(): bool {
        return $this !== self::SVG;
    }
}
```

---

## Data Entities

### DataSeries

**Purpose**: Represents a single series of data points with styling

**Type**: Immutable value object

```php
namespace Codryn\PHPFastChart\Data;

final readonly class DataSeries
{
    /** @var array<DataPoint> */
    private array $points;
    
    /**
     * @param string $name Series name (required, non-empty)
     * @param array<DataPoint> $points Data points (at least one required)
     * @param string $lineColor Hex or named color
     * @param string|null $fillColor Hex or named color for area fill
     * @param bool $visible Whether series is rendered
     */
    public function __construct(
        private string $name,
        array $points,
        private string $lineColor = '#0066CC',
        private ?string $fillColor = null,
        private bool $visible = true
    ) {
        if ($name === '') {
            throw new InvalidArgumentException('DataSeries name cannot be empty');
        }
        
        if (count($points) === 0) {
            throw new InvalidArgumentException('DataSeries must contain at least one DataPoint');
        }
        
        // Deep copy to ensure immutability
        $this->points = array_map(
            fn(DataPoint $p) => new DataPoint($p->x, $p->y, $p->label),
            $points
        );
    }
    
    public function getName(): string;
    public function getPoints(): array;
    public function getLineColor(): string;
    public function getFillColor(): ?string;
    public function isVisible(): bool;
    public function getPointCount(): int;
}
```

**Validation Rules**:
- Name: non-empty string
- Points: minimum 1 point
- Colors: validated during rendering (lazy validation)
- Immutability: Deep copy of points in constructor

---

### DataPoint

**Purpose**: Single data point in a series

**Type**: Immutable value object (readonly)

```php
namespace Codryn\PHPFastChart\Data;

final readonly class DataPoint
{
    /**
     * @param int|float|string $x X-coordinate or category label
     * @param float|int|null $y Y-coordinate value (null for pie chart labels)
     * @param string|null $label Optional label for this point
     */
    public function __construct(
        public int|float|string $x,
        public int|float|null $y = null,
        public ?string $label = null
    ) {}
    
    public function hasLabel(): bool {
        return $this->label !== null;
    }
}
```

**Special Cases**:
- **Pie Charts**: `x` is category name, `y` is value, label optional
- **Scatter**: Both `x` and `y` are numeric
- **Bar/Line**: `x` is category or numeric, `y` is value

---

### DataCollection

**Purpose**: Container for multiple DataSeries with validation

**Type**: Mutable collection (internal to Chart)

```php
namespace Codryn\PHPFastChart\Data;

final class DataCollection
{
    /** @var array<DataSeries> */
    private array $series = [];
    
    public function add(DataSeries $series): void {
        $this->series[] = $series;
    }
    
    public function getAll(): array {
        return $this->series;
    }
    
    public function isEmpty(): bool {
        return count($this->series) === 0;
    }
    
    public function getSeriesCount(): int {
        return count($this->series);
    }
    
    public function getTotalPointCount(): int {
        return array_sum(array_map(
            fn(DataSeries $s) => $s->getPointCount(),
            $this->series
        ));
    }
}
```

---

## Configuration Entities

### ColorConfiguration

**Purpose**: Centralized color management for all chart elements

**Type**: Mutable configuration object

```php
namespace Codryn\PHPFastChart\Configuration;

final class ColorConfiguration
{
    private string $background = '#FFFFFF';
    private string $axis = '#000000';
    private string $grid = '#CCCCCC';
    private string $text = '#000000';
    
    /** @var array<string, string> Series name => color */
    private array $seriesColors = [];
    
    public function setBackground(string $color): self;
    public function setAxis(string $color): self;
    public function setGrid(string $color): self;
    public function setText(string $color): self;
    public function setSeriesColor(string $seriesName, string $color): self;
    
    public function getBackground(): string;
    public function getAxis(): string;
    public function getGrid(): string;
    public function getText(): string;
    public function getSeriesColor(string $seriesName): ?string;
}
```

**Default Colors**:
- Background: White (#FFFFFF)
- Axis: Black (#000000)
- Grid: Light gray (#CCCCCC)
- Text: Black (#000000)

---

### AxisConfiguration

**Purpose**: Configuration for X or Y axis

**Type**: Mutable configuration object

```php
namespace Codryn\PHPFastChart\Configuration;

final class AxisConfiguration
{
    private string $name; // 'x' or 'y'
    private ?string $label = null;
    private ?float $min = null;
    private ?float $max = null;
    private bool $autoScale = true;
    private ?float $tickInterval = null;
    private AxisClipMode $clipMode = AxisClipMode::Throw;
    
    public function __construct(string $name) {
        $this->name = $name;
    }
    
    public function setLabel(string $label): self;
    public function setRange(?float $min, ?float $max): self;
    public function setAutoScale(bool $auto): self;
    public function setTickInterval(float $interval): self;
    public function setClipMode(AxisClipMode $mode): self;
    
    public function isAutoScale(): bool {
        return $this->autoScale || ($this->min === null && $this->max === null);
    }
}
```

**Clip Modes**:
```php
enum AxisClipMode: string {
    case Throw = 'throw';    // Throw exception on out-of-range data
    case Clip = 'clip';      // Silently clip to range
}
```

---

### GridConfiguration

**Purpose**: Configuration for grid lines

**Type**: Immutable value object

```php
namespace Codryn\PHPFastChart\Configuration;

final readonly class GridConfiguration
{
    public function __construct(
        private bool $horizontalEnabled = false,
        private bool $verticalEnabled = false,
        private ?float $horizontalSpacing = null,
        private ?float $verticalSpacing = null,
        private int $lineWidth = 1,
        private string $lineColor = '#CCCCCC'
    ) {}
    
    public function isHorizontalEnabled(): bool;
    public function isVerticalEnabled(): bool;
    public function getHorizontalSpacing(): ?float;
    public function getVerticalSpacing(): ?float;
    public function getLineWidth(): int;
    public function getLineColor(): string;
    
    public static function disabled(): self {
        return new self();
    }
}
```

**Auto-spacing**: If spacing is null, calculated automatically based on axis range

---

### LegendConfiguration

**Purpose**: Configuration for chart legend

**Type**: Immutable value object

```php
namespace Codryn\PHPFastChart\Configuration;

final readonly class LegendConfiguration
{
    public function __construct(
        private bool $enabled = true,
        private LegendPosition $position = LegendPosition::TopRight,
        private int $fontSize = 12,
        private string $fontColor = '#000000',
        private string $backgroundColor = '#FFFFFF',
        private int $padding = 10
    ) {}
    
    public function isEnabled(): bool;
    public function getPosition(): LegendPosition;
    public function getFontSize(): int;
    public function getFontColor(): string;
    public function getBackgroundColor(): string;
    public function getPadding(): int;
}

enum LegendPosition: string {
    case TopLeft = 'top-left';
    case TopRight = 'top-right';
    case BottomLeft = 'bottom-left';
    case BottomRight = 'bottom-right';
    case Right = 'right';
}
```

---

## Renderer Interfaces

### RendererInterface

**Purpose**: Contract for image format renderers (GD vs SVG)

```php
namespace Codryn\PHPFastChart\Renderer;

interface RendererInterface
{
    /**
     * Initialize renderer with image dimensions
     */
    public function initialize(int $width, int $height): void;
    
    /**
     * Render the complete chart
     */
    public function render(Chart $chart): void;
    
    /**
     * Save rendered image to file
     */
    public function save(string $path): void;
    
    /**
     * Get rendered image as string
     */
    public function getOutput(): string;
    
    /**
     * Clean up resources
     */
    public function cleanup(): void;
}
```

---

### ChartRendererInterface

**Purpose**: Strategy for rendering specific chart types

```php
namespace Codryn\PHPFastChart\Renderer\ChartRenderer;

interface ChartRendererInterface
{
    /**
     * Render chart data to the given renderer
     */
    public function render(
        RendererInterface $renderer,
        DataCollection $data,
        AxisConfiguration $xAxis,
        AxisConfiguration $yAxis,
        int $plotX,
        int $plotY,
        int $plotWidth,
        int $plotHeight
    ): void;
}
```

**Implementations**:
- BarChartRenderer
- LineChartRenderer  
- PieChartRenderer
- ScatterChartRenderer
- RadarChartRenderer

---

## Exception Hierarchy

```php
namespace Codryn\PHPFastChart\Exception;

// Base exception
class ChartException extends \Exception {}

// Input validation errors
class InvalidArgumentException extends ChartException {}

// Configuration errors
class InvalidConfigurationException extends ChartException {}

// Rendering errors
class RenderException extends ChartException {}
```

**Usage Examples**:
- `InvalidArgumentException`: Empty dataset, invalid dimensions, bad color format
- `InvalidConfigurationException`: Conflicting axis settings, unsupported format
- `RenderException`: GD resource creation failed, file write error

---

## Utility Classes

### ColorParser

**Purpose**: Parse and convert color formats

```php
namespace Codryn\PHPFastChart\Util;

final class ColorParser
{
    /**
     * Parse color string to RGBA array
     * @return array{r: int, g: int, b: int, a: int}
     */
    public static function parse(string $color): array;
    
    /**
     * Convert to GD alpha (0-127)
     */
    public static function toGdAlpha(int $alpha): int;
    
    /**
     * Convert to SVG alpha (0.0-1.0)
     */
    public static function toSvgAlpha(int $alpha): float;
}
```

---

### MathUtil

**Purpose**: Coordinate transformations and calculations

```php
namespace Codryn\PHPFastChart\Util;

final class MathUtil
{
    /**
     * Transform data coordinate to pixel coordinate
     */
    public static function dataToPixel(
        float $dataValue,
        float $dataMin,
        float $dataMax,
        int $pixelMin,
        int $pixelMax,
        bool $invertY = false
    ): int;
    
    /**
     * Calculate nice number for axis labels
     */
    public static function calculateNiceNumber(float $range, bool $round = true): float;
    
    /**
     * Calculate grid spacing
     */
    public static function calculateGridSpacing(float $min, float $max, int $targetDivisions = 7): float;
    
    /**
     * Convert polar to Cartesian coordinates
     */
    public static function polarToCartesian(
        float $centerX,
        float $centerY,
        float $radius,
        float $angleRadians
    ): array;
}
```

---

### Validator

**Purpose**: Input validation helpers

```php
namespace Codryn\PHPFastChart\Util;

final class Validator
{
    /**
     * @throws InvalidArgumentException
     */
    public static function validateDimensions(int $width, int $height): void;
    
    /**
     * @throws InvalidArgumentException
     */
    public static function validateNonEmpty(string $value, string $fieldName): void;
    
    /**
     * @throws InvalidArgumentException
     */
    public static function validateColorFormat(string $color): void;
    
    /**
     * @throws InvalidArgumentException
     */
    public static function validateRange(float $min, float $max, string $axisName): void;
}
```

---

## Relationships Summary

| Entity | Contains/Uses | Cardinality |
|--------|---------------|-------------|
| Chart | ChartType | 1 |
| Chart | DataCollection | 1 |
| Chart | ColorConfiguration | 1 |
| Chart | AxisConfiguration | 2 (X, Y) |
| Chart | GridConfiguration | 0..1 |
| Chart | LegendConfiguration | 0..1 |
| DataCollection | DataSeries | 1..n |
| DataSeries | DataPoint | 1..n |
| Chart | RendererInterface | 1 (runtime) |
| RendererInterface | ChartRendererInterface | 1 (runtime) |

---

## Immutability Strategy

| Entity | Mutability | Reasoning |
|--------|-----------|-----------|
| Chart | Mutable | Fluent interface requires mutation during configuration |
| DataSeries | Immutable | Prevents external modification of data |
| DataPoint | Immutable | Value object, no need for mutation |
| DataCollection | Mutable | Internal collection, encapsulated by Chart |
| ColorConfiguration | Mutable | Part of Chart configuration |
| AxisConfiguration | Mutable | Part of Chart configuration |
| GridConfiguration | Immutable | Created once, no modification needed |
| LegendConfiguration | Immutable | Created once, no modification needed |

**Key Principle**: User-facing data entities (DataSeries, DataPoint) are immutable. Internal configuration objects can be mutable for fluent interface convenience.

---

## Next Steps

1. ✅ Data model documented
2. ⏭️ Create API contracts in `contracts/` directory
3. ⏭️ Create `quickstart.md` with usage examples
4. ⏭️ Update agent context
5. ⏭️ Generate `tasks.md` for implementation
