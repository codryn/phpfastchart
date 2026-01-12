# Research: Chart Generation Technologies & Patterns

**Feature**: 2D Chart Generator with Configurable Rendering
**Date**: 2026-01-12
**Phase**: 0 - Research & Design Decisions

## 1. GD Extension Capabilities

### Decision: Use PHP GD extension for raster image generation

**Rationale**: 
- GD is bundled with PHP since 4.3, available in all target versions (8.1-8.5)
- Supports PNG and WEBP formats natively
- Provides all necessary drawing primitives (lines, rectangles, arcs, text)
- Zero additional dependencies (already part of standard PHP installation)

**Key Capabilities Verified**:
- **PNG Support**: `imagepng()` - Full support with transparency via `imagealphablending()`
- **WEBP Support**: `imagewebp()` - Available since PHP 5.0, quality parameter 0-100
- **True Color**: `imagecreatetruecolor()` - 24-bit color with alpha channel
- **Anti-aliasing**: `imageantialias()` - Smooth lines for better appearance
- **Color Management**: `imagecolorallocatealpha()` - RGBA colors with 0-127 alpha (0=opaque, 127=transparent)
- **Text Rendering**: Built-in fonts (1-5) via `imagestring()`, or TrueType via `imagettftext()` (for future enhancement)
- **Drawing Primitives**: Lines, rectangles, filled polygons, arcs, ellipses

**Limitations**:
- Memory usage: Approximately `width × height × 4 bytes` per image
- For 4000×4000 image: ~64MB memory required
- No built-in anti-aliasing for filled areas (but acceptable for charts)
- Built-in fonts are bitmap fonts (low quality, but fast and dependency-free)

**Alternatives Considered**:
- **Imagick**: More powerful but requires external ImageMagick installation (violates zero-dependency principle)
- **Third-party libraries**: Would add Composer dependencies (rejected per constitution)

## 2. SVG Generation Patterns

### Decision: Pure PHP string generation for SVG XML

**Rationale**:
- SVG is XML-based text format - no binary processing needed
- Zero dependencies, perfect control over output
- Full PHP 8.1+ compatibility
- Easier to test (just string comparison)

**Implementation Pattern**:
```php
class SvgRenderer implements RendererInterface {
    private string $svg = '';
    
    public function render(Chart $chart): void {
        $this->svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d">',
            $chart->getWidth(),
            $chart->getHeight()
        );
        // Build XML string
        $this->svg .= '</svg>';
    }
    
    public function save(string $path): void {
        file_put_contents($path, $this->svg);
    }
}
```

**SVG Elements Needed**:
- `<rect>` - Bars, background, grid lines
- `<line>` - Axes, grid, connectors
- `<path>` - Complex shapes (pie slices, radar polygons)
- `<circle>` - Scatter points
- `<text>` - Labels, legend, titles
- `<g>` - Grouping elements (for series, legend)

**Path Generation for Complex Charts**:
- **Pie Chart**: Use arc commands in `<path d="M x,y A rx,ry...">`
- **Radar Chart**: `<polygon points="x1,y1 x2,y2 ...">` or path with line commands

**Browser Compatibility**: All modern browsers support SVG 1.1 (IE9+, all mobile browsers)

**Alternatives Considered**:
- **SVG libraries**: None needed - format is simple enough for direct generation
- **Canvas export to SVG**: Would require JavaScript (server-side PHP can't use canvas)

## 3. Chart Rendering Mathematics

### Decision: Transform data space to pixel space with proper scaling

**Coordinate System**: 
- **Screen Coordinates**: Origin top-left (0,0), Y increases downward - used by GD and SVG
- **Data Coordinates**: Traditional Cartesian, origin bottom-left, Y increases upward
- **Transformation Required**: Invert Y-axis when rendering

**Coordinate Transformation Formula**:
```php
// Data to pixel conversion
$pixelX = $marginLeft + (($dataX - $xMin) / ($xMax - $xMin)) * $plotWidth;
$pixelY = $marginTop + $plotHeight - (($dataY - $yMin) / ($yMax - $yMin)) * $plotHeight;

// Where:
// - plotWidth = imageWidth - marginLeft - marginRight
// - plotHeight = imageHeight - marginTop - marginBottom
// - margins reserve space for axes, labels, legend
```

**Auto-scaling Algorithm**:
```php
// Find data range
$yMin = min(array_map(fn($p) => $p->y, $allPoints));
$yMax = max(array_map(fn($p) => $p->y, $allPoints));

// Add 10% padding for visual breathing room
$range = $yMax - $yMin;
$yMin -= $range * 0.05;
$yMax += $range * 0.05;

// Round to nice numbers for axis labels
$yMin = floor($yMin / $niceNumber) * $niceNumber;
$yMax = ceil($yMax / $niceNumber) * $niceNumber;
```

**Grid Line Spacing Calculation**:
```php
// Target 5-10 divisions per axis
$range = $max - $min;
$roughStep = $range / 7; // Aim for 7 divisions

// Round to nearest "nice" number: 1, 2, 5, 10, 20, 50, 100, etc.
$magnitude = pow(10, floor(log10($roughStep)));
$normalized = $roughStep / $magnitude;

$niceStep = $normalized < 1.5 ? 1 : ($normalized < 3 ? 2 : ($normalized < 7 ? 5 : 10));
$gridSpacing = $niceStep * $magnitude;
```

**Polar Coordinates for Radar Charts**:
```php
// Each axis is evenly spaced around 360°
$angleStep = 2 * M_PI / $dimensionCount;

// Convert polar to Cartesian
$angle = $startAngle + ($index * $angleStep);
$x = $centerX + ($radius * $value / $maxValue) * cos($angle);
$y = $centerY + ($radius * $value / $maxValue) * sin($angle);
```

**Alternatives Considered**:
- **Fixed scaling**: Too inflexible for varied datasets
- **Logarithmic scaling**: Out of scope for MVP, can be added later

## 4. Color Parsing Standards

### Decision: Support hex colors and named colors with standardized parsing

**Color Format Support**:
- `#RGB` - 3-digit hex (expanded to RRGGBB)
- `#RRGGBB` - 6-digit hex
- `#RRGGBBAA` - 8-digit hex with alpha
- Named colors: `'red'`, `'blue'`, `'green'`, etc. (CSS3 named colors)

**Implementation**:
```php
class ColorParser {
    private const NAMED_COLORS = [
        'red' => [255, 0, 0],
        'green' => [0, 128, 0],
        'blue' => [0, 0, 255],
        'white' => [255, 255, 255],
        'black' => [0, 0, 0],
        // ... full CSS3 color list
    ];
    
    public static function parse(string $color): array {
        if ($color[0] === '#') {
            return self::parseHex($color);
        }
        return self::parseNamed($color);
    }
    
    private static function parseHex(string $hex): array {
        $hex = ltrim($hex, '#');
        $len = strlen($hex);
        
        if ($len === 3) { // #RGB -> #RRGGBB
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $a = $len === 8 ? hexdec(substr($hex, 6, 2)) : 255;
        
        return ['r' => $r, 'g' => $g, 'b' => $b, 'a' => $a];
    }
}
```

**Alpha Channel Handling**:
- **GD**: Uses 0-127 scale (0=opaque, 127=transparent) - requires conversion from 0-255
- **SVG**: Uses 0.0-1.0 scale or 0-255 in rgba() - conversion needed
- **Conversion**: `gdAlpha = 127 - (alpha * 127 / 255)`

**Transparency in Different Formats**:
- **PNG**: Full alpha channel support via RGBA
- **WEBP**: Full alpha channel support
- **SVG**: Full alpha channel support via `fill-opacity` or `rgba()`

**Alternatives Considered**:
- **RGB object**: Would require users to create objects (less user-friendly)
- **Array format**: `[255, 0, 0]` - could be added as alternative input

## 5. Design Pattern Selection

### Decision: Strategy + Fluent Interface + Immutable Value Objects

**Primary Patterns**:

1. **Strategy Pattern for Chart Renderers**
   ```php
   interface ChartRendererInterface {
       public function render(
           RendererInterface $renderer,
           DataCollection $data,
           AxisConfiguration $xAxis,
           AxisConfiguration $yAxis
       ): void;
   }
   ```
   - Each chart type implements strategy
   - Easy to add new chart types without modifying existing code
   - Testable in isolation

2. **Fluent Interface for Configuration**
   ```php
   public function setSize(int $width, int $height): self {
       $this->width = $width;
       $this->height = $height;
       return $this;
   }
   ```
   - Chainable method calls
   - Clear, readable API
   - IDE autocomplete friendly

3. **Immutable Value Objects for Data**
   ```php
   final readonly class DataPoint {
       public function __construct(
           public int|float|string $x,
           public int|float|null $y,
           public ?string $label = null
       ) {}
   }
   ```
   - PHP 8.1+ readonly properties
   - Prevents accidental mutations
   - Thread-safe (though PHP is single-threaded)

4. **Template Method for Rendering Pipeline**
   ```php
   abstract class AbstractRenderer implements RendererInterface {
       public function render(Chart $chart): void {
           $this->initialize($chart);
           $this->renderBackground();
           $this->renderGrid();
           $this->renderAxes();
           $this->renderData();
           $this->renderLegend();
           $this->finalize();
       }
       
       abstract protected function renderBackground(): void;
       // ... other abstract methods
   }
   ```

**Exception Hierarchy**:
```
ChartException (base)
├── InvalidArgumentException (invalid inputs)
├── InvalidConfigurationException (conflicting settings)
└── RenderException (rendering failures)
```

**Alternatives Considered**:
- **Builder pattern**: More verbose than fluent interface, less intuitive
- **Mutable data objects**: Unsafe, can lead to bugs
- **Factory pattern**: Unnecessary complexity for chart type selection

## 6. Performance Considerations

### Memory Management

**Expected Memory Usage**:
- **Small chart** (800×600): ~2MB (image buffer + overhead)
- **Large chart** (4000×4000): ~64MB (image buffer + overhead)
- **10,000 data points**: ~2MB (DataPoint objects)

**Optimization Strategies**:
- Don't keep multiple image resources in memory simultaneously
- Clear image resource after save: `imagedestroy($resource)`
- For SVG, build string incrementally (but don't store rendered raster)
- Limit maximum dataset size to 10,000 points (throws exception if exceeded)

### Rendering Performance

**Target**: <1 second for 1,000 data points

**Performance Bottlenecks**:
- Coordinate transformations: O(n) where n = data points
- Drawing operations: O(n) for lines/points
- Text rendering: O(m) where m = labels (relatively slow in GD)

**Optimization Opportunities**:
- Cache calculated coordinate transformations
- Batch drawing operations where possible
- Skip rendering invisible points (outside viewport)
- Use faster built-in GD fonts instead of TrueType for MVP

## 7. Error Handling Strategy

### When to Throw Exceptions

**Validation Failures (InvalidArgumentException)**:
- Empty dataset (no data points)
- Dimensions below minimum (50×50 pixels)
- Dimensions above maximum (4000×4000 pixels - configurable)
- Invalid color format
- Data outside axis range (when clip mode disabled)
- Invalid file path for output

**Configuration Errors (InvalidConfigurationException)**:
- Conflicting settings (e.g., auto-scale + manual min/max)
- Unsupported format for platform (WEBP without GD support)
- Legend position outside image bounds

**Rendering Failures (RenderException)**:
- GD resource creation failure (out of memory)
- File write permission denied
- Disk full when saving

### When to Use Defaults

**Acceptable Defaults (no exception)**:
- Missing colors → use default color scheme
- No grid configuration → disable grid
- No legend → don't render legend
- No axis labels → render unlabeled axes

**Principle**: Fail fast for data/validation issues, use sensible defaults for optional styling.

## Summary of Key Decisions

| Decision Area | Choice | Rationale |
|---------------|--------|-----------|
| Raster Format | GD Extension | Zero dependencies, bundled with PHP |
| Vector Format | Pure PHP SVG | No dependencies, full control |
| API Style | Fluent Interface | User-friendly, chainable, clear |
| Data Handling | Immutable Copy | Prevents external mutation bugs |
| Out-of-Range Data | Exception + Optional Clip | Safe default, configurable behavior |
| Empty Datasets | Throw Exception | Likely programmer error |
| Min Dimensions | 50×50 pixels | Ensures readability |
| Coordinate System | Screen coords with Y-inversion | Matches GD/SVG, handles Cartesian data |
| Auto-scaling | Nice number rounding | Clean axis labels |
| Grid Spacing | Target 5-10 divisions | Readable without clutter |
| Color Formats | Hex + named colors | Standard, user-friendly |
| Chart Renderers | Strategy Pattern | Extensible, testable |
| Performance | <1s for 1K points | Acceptable for most use cases |

## References

- [PHP GD Documentation](https://www.php.net/manual/en/book.image.php)
- [SVG Specification](https://www.w3.org/TR/SVG11/)
- [CSS3 Named Colors](https://www.w3.org/TR/css-color-3/#svg-color)
- [PSR-12 Coding Style](https://www.php-fig.org/psr/psr-12/)
- [PHPStan Level 10 Rules](https://phpstan.org/user-guide/rule-levels)
