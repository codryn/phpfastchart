# Implementation Plan: 2D Chart Generator with Configurable Rendering

**Branch**: `001-generate-charts` | **Date**: 2026-01-12 | **Spec**: [spec.md](spec.md)
**Input**: Feature specification from `/specs/001-generate-charts/spec.md`

## Summary

PHPFastChart will generate 2D charts in PNG, WEBP, and SVG formats with full customization of colors, grid lines, labels, legends, and axis scaling. The library supports five chart types (Bar, Line, Pie, Scatter, Radar) and uses a fluent interface API for configuration. Implementation follows strict TDD methodology with zero runtime dependencies beyond PHP's GD extension for raster formats and pure PHP for SVG generation.

**Technical Approach**: Use Strategy pattern for chart type renderers, Builder/Fluent interface for configuration, and separate rendering engines for raster (GD) vs vector (SVG) formats. All data is copied immutably on input to prevent external modifications from affecting chart output.

## Technical Context

**Language/Version**: PHP 8.1-8.5 (dev environment: PHP 8.3)
**Primary Dependencies**: None (zero runtime dependencies - development only: PHPUnit, PHPStan, PHP-CS-Fixer)
**Storage**: File system output (PNG, WEBP, SVG files)
**Testing**: PHPUnit 10.5+ with >= 80% coverage requirement
**Target Platform**: Library only - usable in CLI, Web/CGI, or any PHP environment
**Project Type**: PHP Library (single project structure)
**Performance Goals**: Generate charts with up to 1,000 data points in <1 second on standard hardware
**Constraints**: Zero runtime dependencies, PSR-12, PHPStan level 10 with strict rules, PHP 8.1 compatibility
**Scale/Scope**: Support 5 chart types, 3 output formats, datasets up to 10,000 points, images up to 4000x4000 pixels

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

All features MUST comply with PHPFastChart constitution principles:

- [x] **PHP Compatibility**: Works on PHP 8.1, 8.2, 8.3, 8.4, 8.5 - Using only GD extension (standard since PHP 4.3) and pure PHP for SVG
- [x] **Zero Dependencies**: No new runtime dependencies (development dependencies OK) - GD is standard extension, SVG is pure PHP
- [x] **PHPStan Level 10**: Passes strict rules with zero errors - Design uses full type declarations and immutable data patterns
- [x] **PSR-12**: Code style compliant - All code will be formatted with PHP-CS-Fixer
- [x] **Strict Types**: `declare(strict_types=1)` in all files - Enforced via verification script
- [x] **TDD**: Tests written and approved before implementation - Spec includes acceptance criteria for each user story
- [x] **PHPDoc**: Complete documentation for all public APIs - Documented in FR-025
- [x] **Test Coverage**: >= 80% coverage maintained or improved - Success criterion SC-005
- [x] **CI Pipeline**: Tests pass on all PHP versions - Success criterion SC-008
- [x] **Documentation**: User-facing docs updated - Examples and quickstart will be created

**Constitution Compliance**: ✅ ALL GATES PASS - No exceptions needed

## Project Structure

### Documentation (this feature)

```text
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)

```text
src/
├── Chart/
│   ├── Chart.php                    # Main chart class with fluent interface
│   ├── ChartType.php                # Enum for chart types (Bar, Line, Pie, Scatter, Radar)
│   └── ChartBuilder.php             # Optional builder for complex configurations
├── Data/
│   ├── DataSeries.php               # Immutable data series with name, points, colors
│   ├── DataPoint.php                # Single data point (x, y, label)
│   └── DataCollection.php           # Collection of DataSeries with validation
├── Configuration/
│   ├── ImageConfiguration.php       # Width, height, format (PNG/WEBP/SVG), quality
│   ├── ColorConfiguration.php       # Background, axis, grid, series colors
│   ├── AxisConfiguration.php        # Min, max, auto-scale, label, color, clip mode
│   ├── GridConfiguration.php        # Horizontal/vertical enabled, spacing, line style
│   └── LegendConfiguration.php      # Enabled, position, font, colors
├── Renderer/
│   ├── RendererInterface.php        # Contract for all renderers
│   ├── RasterRenderer.php           # GD-based renderer for PNG/WEBP
│   ├── SvgRenderer.php              # Pure PHP SVG XML generator
│   └── ChartRenderer/
│       ├── ChartRendererInterface.php
│       ├── BarChartRenderer.php
│       ├── LineChartRenderer.php
│       ├── PieChartRenderer.php
│       ├── ScatterChartRenderer.php
│       └── RadarChartRenderer.php
├── Exception/
│   ├── ChartException.php           # Base exception
│   ├── InvalidArgumentException.php # For invalid inputs
│   ├── InvalidConfigurationException.php
│   └── RenderException.php          # For rendering failures
└── Util/
    ├── ColorParser.php              # Parse hex, named colors to RGB/RGBA
    ├── MathUtil.php                 # Coordinate transformations, scaling
    └── Validator.php                # Input validation helpers

tests/
├── Unit/
│   ├── Chart/
│   │   ├── ChartTest.php
│   │   └── ChartTypeTest.php
│   ├── Data/
│   │   ├── DataSeriesTest.php
│   │   ├── DataPointTest.php
│   │   └── DataCollectionTest.php
│   ├── Configuration/
│   │   └── [ConfigTest.php for each config class]
│   ├── Renderer/
│   │   ├── RasterRendererTest.php
│   │   ├── SvgRendererTest.php
│   │   └── ChartRenderer/
│   │       └── [RendererTest.php for each chart type]
│   └── Util/
│       ├── ColorParserTest.php
│       ├── MathUtilTest.php
│       └── ValidatorTest.php
├── Integration/
│   ├── ChartGenerationTest.php      # End-to-end chart generation tests
│   ├── FormatCompatibilityTest.php  # PNG, WEBP, SVG output validation
│   └── ChartTypeTest.php            # All 5 chart types full workflow
└── Fixtures/
    ├── SampleData.php               # Test datasets
    ├── ExpectedImages/              # Reference images for comparison
    └── TestColors.php               # Color test data

examples/
├── basic-line-chart.php
├── bar-chart-with-legend.php
├── pie-chart-custom-colors.php
├── scatter-plot.php
├── radar-chart.php
├── svg-export.php
└── advanced-styling.php
```

**Structure Decision**: Using PHP Library structure with clear separation of concerns:
- **Chart/**: Public API and chart management
- **Data/**: Immutable data models
- **Configuration/**: Type-safe configuration objects
- **Renderer/**: Strategy pattern for format and chart type rendering
- **Exception/**: Typed exception hierarchy
- **Util/**: Shared utilities for parsing and math

## Complexity Tracking

**No constitutional violations** - All gates pass without exceptions. No complexity justification needed.

---

## Phase 0: Research & Design Decisions

**Output**: `research.md`

### Research Tasks

1. **GD Extension Capabilities**
   - Verify PNG and WEBP support across PHP 8.1-8.5
   - Document color handling (RGB, RGBA, transparency)
   - Confirm anti-aliasing support for lines and text
   - Research memory limits for large images

2. **SVG Generation Patterns**
   - Best practices for pure PHP SVG XML generation
   - Path generation for complex charts (radar, pie)
   - Text positioning and styling in SVG
   - Browser compatibility for SVG features used

3. **Chart Rendering Mathematics**
   - Coordinate system transformations (data space → pixel space)
   - Auto-scaling algorithms for axes
   - Grid line spacing calculation
   - Polar coordinate conversion for radar charts

4. **Color Parsing Standards**
   - PHP-compatible hex color parsing
   - Named color mappings
   - Alpha channel handling differences between GD and SVG
   - Transparency in PNG vs WEBP vs SVG

5. **Design Pattern Selection**
   - Strategy pattern for chart type renderers
   - Fluent interface implementation patterns
   - Immutability patterns for data objects
   - Exception hierarchy best practices

### Key Decisions to Document

- **Image Format Selection Logic**: How library chooses/validates PNG/WEBP/SVG
- **Coordinate System**: Standard Cartesian (origin bottom-left) vs screen coordinates (origin top-left)
- **Text Rendering**: GD built-in fonts vs TrueType (if needed later)
- **Performance Optimizations**: Caching strategies, memory management
- **Error Handling Strategy**: When to throw vs when to use defaults

---

## Phase 1: Architecture & Contracts

**Output**: `data-model.md`, `contracts/`, `quickstart.md`

### data-model.md Content

Document all entities from specification with PHP-specific details:

**Chart Entity**
- Properties: type (ChartType enum), dimensions, dataCollection, configurations array
- Immutability: Configurations are value objects, data is cloned on input
- Validation: Happens at generate() time, not during configuration

**DataSeries Entity**
- Properties: name (string), points (array<DataPoint>), lineColor, fillColor, visible
- Immutability: Deep copy of points array in constructor
- Validation: Non-empty name required, at least one point required

**DataPoint Entity**
- Properties: x (float|int|string), y (float|int|null), label (?string)
- Immutability: Readonly properties (PHP 8.1+)
- Special cases: Pie charts use only y values, x is category label

**Configuration Entities**
- All configuration objects are immutable value objects
- Implement fluent setters that return new instances (or use Chart methods)
- Validation in constructors with typed exceptions

**Renderer Architecture**
- RendererInterface: `render(Chart $chart): void` and `save(string $path): void`
- RasterRenderer uses GD resource, SvgRenderer builds XML string
- ChartRendererInterface: Strategy for each chart type

### contracts/ Directory

Create API contract specifications:

**contracts/chart-api.md**
```php
// Public API surface
$chart = new Chart(ChartType::Line);
$chart->setSize(800, 600)
      ->setFormat(ImageFormat::PNG)
      ->setBackgroundColor('#FFFFFF')
      ->addDataSeries($series)
      ->enableGrid(horizontal: true, vertical: true)
      ->setAxisRange('y', min: 0, max: 100)
      ->enableLegend(position: 'top-right')
      ->generate('/path/to/output.png');
```

**contracts/exceptions.md**
- Document all exception types and when they're thrown
- Include example error messages with context

**contracts/color-formats.md**
- Supported color formats: `#RGB`, `#RRGGBB`, `#RRGGBBAA`, named colors
- List of supported named colors
- Alpha channel behavior per format

### quickstart.md Content

**Installation**
```bash
composer require codryn/phpfastchart
```

**Minimal Example** (Line Chart)
```php
<?php declare(strict_types=1);

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Data\DataPoint;

$series = new DataSeries('Sales', [
    new DataPoint(1, 100),
    new DataPoint(2, 150),
    new DataPoint(3, 120),
]);

$chart = new Chart(ChartType::Line);
$chart->setSize(800, 600)
      ->addDataSeries($series)
      ->generate('chart.png');
```

**Configuration Examples**
- Custom colors
- Grid lines
- Multiple data series
- All three output formats

### Agent Context Update

Run update script to add new technologies to agent context:
```bash
.specify/scripts/bash/update-agent-context.sh copilot
```

New entries:
- GD Extension usage patterns
- SVG generation with pure PHP
- Fluent interface patterns
- Immutability patterns for PHP 8.1+

---

## Re-evaluation: Constitution Check Post-Design

*GATE: Verify all constitutional principles still satisfied after architecture design.*

- [x] **PHP Compatibility**: ✅ GD available in all versions, pure PHP SVG generation
- [x] **Zero Dependencies**: ✅ No Composer runtime dependencies added
- [x] **PHPStan Level 10**: ✅ All entities use strict types, no mixed types, readonly properties
- [x] **PSR-12**: ✅ Naming conventions follow PSR standards
- [x] **Strict Types**: ✅ All files will have strict types declaration
- [x] **TDD**: ✅ Architecture supports unit and integration testing
- [x] **PHPDoc**: ✅ All interfaces and public methods will be documented
- [x] **Test Coverage**: ✅ Testable design with dependency injection
- [x] **CI Pipeline**: ✅ Standard test execution across PHP versions
- [x] **Documentation**: ✅ Quickstart and examples planned

**Post-Design Compliance**: ✅ ALL GATES STILL PASS

---

## Implementation Phases (Summary)

**Phase 0**: Research (this plan command output)
- Document GD capabilities, SVG patterns, rendering math
- Output: research.md

**Phase 1**: Architecture & Contracts (this plan command output)  
- Design data models, define API contracts, create quickstart
- Output: data-model.md, contracts/, quickstart.md
- Update agent context with new patterns

**Phase 2**: Task Breakdown (run `/speckit.tasks` separately)
- Break down into atomic tasks organized by user story
- Output: tasks.md
- Ready for TDD implementation

---

## Next Steps

1. ✅ Plan created - Review architecture and structure
2. ⏭️ Generate research.md (Phase 0 - included below)
3. ⏭️ Generate data-model.md (Phase 1 - included below)
4. ⏭️ Create contracts/ directory with API specifications
5. ⏭️ Create quickstart.md with usage examples
6. ⏭️ Update agent context
7. ⏭️ Run `/speckit.tasks` to create task breakdown
8. ⏭️ Begin TDD implementation

**Structure Decision**: [Document the selected structure and reference the real
directories captured above]

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| [e.g., 4th project] | [current need] | [why 3 projects insufficient] |
| [e.g., Repository pattern] | [specific problem] | [why direct DB access insufficient] |
