# Exception Contracts

**Feature**: 2D Chart Generator
**Version**: 1.0.0
**Date**: 2026-01-12

## Exception Hierarchy

```
\Exception
    └── Codryn\PHPFastChart\Exception\ChartException (base)
            ├── InvalidArgumentException
            ├── InvalidConfigurationException
            └── RenderException
```

---

## ChartException (Base)

**Namespace**: `Codryn\PHPFastChart\Exception`

Base exception for all chart-related errors. Catch this to handle all library exceptions.

```php
class ChartException extends \Exception
{
    // Inherits standard Exception functionality
}
```

**Usage**:
```php
try {
    $chart->generate('output.png');
} catch (ChartException $e) {
    // Handle any chart-related error
    error_log("Chart generation failed: " . $e->getMessage());
}
```

---

## InvalidArgumentException

**Namespace**: `Codryn\PHPFastChart\Exception`

Thrown when invalid input data or parameters are provided.

### When Thrown

1. **Empty Dataset**
   - **Trigger**: No data series added to chart
   - **Message**: `"Cannot generate chart: no data series provided"`
   
2. **Empty DataSeries**
   - **Trigger**: DataSeries created with zero points
   - **Message**: `"DataSeries must contain at least one DataPoint"`

3. **Invalid Dimensions**
   - **Trigger**: Width or height < 50 or > 4000 pixels
   - **Message**: `"Image dimensions must be between 50 and 4000 pixels (got: {width}x{height})"`

4. **Invalid Color Format**
   - **Trigger**: Color string doesn't match supported formats
   - **Message**: `"Invalid color format '{color}'. Expected hex (#RGB, #RRGGBB, #RRGGBBAA) or named color"`

5. **Empty Series Name**
   - **Trigger**: DataSeries name is empty string
   - **Message**: `"DataSeries name cannot be empty"`

6. **Out-of-Range Data (Throw Mode)**
   - **Trigger**: Data point exceeds axis range when clip mode is Throw
   - **Message**: `"Data point (x={x}, y={y}) exceeds Y-axis range [{min}, {max}] in series '{seriesName}'"`

7. **Invalid Axis Name**
   - **Trigger**: Axis name not 'x' or 'y'
   - **Message**: `"Invalid axis '{axis}'. Must be 'x' or 'y'"`

8. **Invalid Axis Range**
   - **Trigger**: Min >= max when setting axis range
   - **Message**: `"Invalid {axis}-axis range: min ({min}) must be less than max ({max})"`

9. **Excessive Data Points**
   - **Trigger**: More than 10,000 total data points
   - **Message**: `"Dataset too large: {count} points (maximum: 10,000)"`

### Example Handling

```php
use Codryn\PHPFastChart\Exception\InvalidArgumentException;

try {
    $chart = new Chart(ChartType::Line);
    $chart->setSize(40, 30); // Too small!
    $chart->generate('output.png');
} catch (InvalidArgumentException $e) {
    echo "Input error: " . $e->getMessage();
    // Output: "Image dimensions must be between 50 and 4000 pixels (got: 40x30)"
}
```

---

## InvalidConfigurationException

**Namespace**: `Codryn\PHPFastChart\Exception`

Thrown when chart configuration has conflicting or impossible settings.

### When Thrown

1. **Unsupported Format**
   - **Trigger**: WEBP requested but GD doesn't support it
   - **Message**: `"Image format 'WEBP' is not supported by this PHP installation. Available formats: PNG, SVG"`

2. **Legend Outside Bounds**
   - **Trigger**: Legend configured but doesn't fit in image dimensions
   - **Message**: `"Legend does not fit in image dimensions (needs {width}x{height}, available: {availWidth}x{availHeight})"`

3. **Conflicting Axis Settings**
   - **Trigger**: Both auto-scale and manual range set (ambiguous)
   - **Message**: `"Cannot use auto-scale with explicit axis range on {axis}-axis"`
   
4. **Invalid Grid Spacing**
   - **Trigger**: Grid spacing <= 0 or > axis range
   - **Message**: `"Invalid grid spacing {spacing} for {axis}-axis range [{min}, {max}]"`

5. **Pie Chart Multi-Series**
   - **Trigger**: Multiple data series added to pie chart
   - **Message**: `"Pie charts only support a single data series (got {count} series)"`

6. **Radar Chart Insufficient Axes**
   - **Trigger**: Radar chart with < 3 dimensions
   - **Message**: `"Radar charts require at least 3 dimensions (got {count})"`

### Example Handling

```php
use Codryn\PHPFastChart\Exception\InvalidConfigurationException;

try {
    $chart = new Chart(ChartType::Pie);
    $chart->addDataSeries($series1);
    $chart->addDataSeries($series2); // Pie chart doesn't support multiple series
    $chart->generate('output.png');
} catch (InvalidConfigurationException $e) {
    echo "Configuration error: " . $e->getMessage();
    // Output: "Pie charts only support a single data series (got 2 series)"
}
```

---

## RenderException

**Namespace**: `Codryn\PHPFastChart\Exception`

Thrown when rendering or file operations fail.

### When Thrown

1. **GD Resource Creation Failed**
   - **Trigger**: `imagecreatetruecolor()` returns false (out of memory)
   - **Message**: `"Failed to create image resource ({width}x{height}). Out of memory?"`

2. **File Write Failed**
   - **Trigger**: Cannot write to output path
   - **Message**: `"Cannot write to file '{path}': {reason}"`
   - **Reasons**: Permission denied, disk full, directory doesn't exist

3. **Directory Not Found**
   - **Trigger**: Output directory doesn't exist
   - **Message**: `"Output directory does not exist: '{directory}'"`

4. **File Permission Denied**
   - **Trigger**: No write permission to output path
   - **Message**: `"Permission denied writing to '{path}'"`

5. **Image Save Failed**
   - **Trigger**: GD image save function returns false
   - **Message**: `"Failed to save {format} image to '{path}'"`

6. **Invalid GD Extension**
   - **Trigger**: GD extension not loaded
   - **Message**: `"GD extension is not loaded. Cannot generate raster images."`

### Example Handling

```php
use Codryn\PHPFastChart\Exception\RenderException;

try {
    $chart = new Chart(ChartType::Bar);
    $chart->addDataSeries($series);
    $chart->generate('/root/readonly/output.png'); // No permission
} catch (RenderException $e) {
    echo "Rendering failed: " . $e->getMessage();
    // Output: "Permission denied writing to '/root/readonly/output.png'"
    
    // Try alternative location
    $chart->generate('/tmp/output.png');
}
```

---

## Complete Error Handling Example

```php
<?php declare(strict_types=1);

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Exception\{
    ChartException,
    InvalidArgumentException,
    InvalidConfigurationException,
    RenderException
};

try {
    $chart = new Chart(ChartType::Line);
    
    // Validate dimensions before setting
    $width = $_GET['width'] ?? 800;
    $height = $_GET['height'] ?? 600;
    
    if ($width < 50 || $width > 4000 || $height < 50 || $height > 4000) {
        throw new InvalidArgumentException(
            "Image dimensions must be between 50 and 4000 pixels"
        );
    }
    
    $chart->setSize($width, $height)
          ->addDataSeries($series)
          ->generate('output.png');
          
    echo "Success!";
    
} catch (InvalidArgumentException $e) {
    // Input validation errors - show to user
    http_response_code(400);
    echo json_encode([
        'error' => 'invalid_input',
        'message' => $e->getMessage()
    ]);
    
} catch (InvalidConfigurationException $e) {
    // Configuration errors - show to user
    http_response_code(400);
    echo json_encode([
        'error' => 'invalid_configuration',
        'message' => $e->getMessage()
    ]);
    
} catch (RenderException $e) {
    // Rendering/IO errors - log and show generic error
    error_log("Chart rendering failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'render_failed',
        'message' => 'Failed to generate chart'
    ]);
    
} catch (ChartException $e) {
    // Catch-all for any other chart errors
    error_log("Unexpected chart error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'unexpected_error',
        'message' => 'An unexpected error occurred'
    ]);
}
```

---

## Error Message Guidelines

All exception messages follow these principles:

1. **Specific**: Include relevant values and context
   - ❌ "Invalid dimensions"
   - ✅ "Image dimensions must be between 50 and 4000 pixels (got: 40x30)"

2. **Actionable**: Tell user what to fix
   - ❌ "Configuration error"
   - ✅ "Pie charts only support a single data series (got 2 series)"

3. **Consistent**: Use standard phrasing
   - "Cannot {action}: {reason}"
   - "{Entity} must be {requirement} (got: {actual})"
   - "Invalid {parameter} '{value}'. Expected {description}"

4. **No Stack Traces in Messages**: Let exception system handle traces
   - Messages describe the problem
   - Stack traces show where it occurred

---

## Testing Exception Behavior

```php
use PHPUnit\Framework\TestCase;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;

class ChartExceptionTest extends TestCase
{
    public function testThrowsOnEmptyDataset(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot generate chart: no data series provided');
        
        $chart = new Chart(ChartType::Line);
        $chart->generate('output.png'); // No data added!
    }
    
    public function testThrowsOnInvalidDimensions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Image dimensions must be between 50 and 4000 pixels (got: 10x10)');
        
        $chart = new Chart(ChartType::Bar);
        $chart->setSize(10, 10);
    }
}
```

---

## Next Steps

See also:
- [chart-api.md](chart-api.md) - Complete API documentation
- [color-formats.md](color-formats.md) - Color format specifications
- [../data-model.md](../data-model.md) - Entity definitions
