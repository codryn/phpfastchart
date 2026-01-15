# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0] - 2026-01-15

### Added

#### Chart Types
- Line charts with multi-series support
- Bar charts with grouped series support
- Scatter plots for X/Y coordinate data
- Pie charts for percentage distributions (single series)
- Radar/spider charts for multi-dimensional comparisons

#### Output Formats
- PNG format (raster) using GD library
- WEBP format (modern compressed raster) using GD library
- SVG format (scalable vector graphics) with pure PHP implementation

#### Customization Features
- **Colors**: Customizable background, axis, grid, and series colors
- **Grid Lines**: Horizontal and vertical grid with configurable spacing and line styles
- **Axis Scaling**: Manual and automatic axis ranges with clip modes (clip, clamp, error)
- **Labels**: Chart titles, X/Y axis labels, and data point labels
- **Legend**: Multi-position legend support (top-left, top-right, bottom-left, bottom-right, left, right, top, bottom)
- **Dimensions**: Flexible chart sizing from small thumbnails to 4000×4000 images

#### Core Architecture
- `Chart` class with fluent interface API
- `ChartType` enum (Line, Bar, Pie, Scatter, Radar)
- `ImageFormat` enum (PNG, WEBP, SVG)
- `DataPoint` immutable value object
- `DataSeries` immutable data container
- `RendererInterface` with `RasterRenderer` and `SvgRenderer` implementations

#### Configuration Classes
- `AxisConfiguration` for axis ranges and scaling
- `AxisClipMode` enum (Clip, Clamp, Error)
- `ColorConfiguration` for color management
- `GridConfiguration` for grid line styling
- `LegendConfiguration` for legend customization
- `LegendPosition` enum for legend placement

#### Utilities
- `ColorParser` for hex and named color parsing
- `MathUtil` for coordinate transformations and scaling calculations
- `Validator` for input validation

#### Exception Hierarchy
- `ChartException` base exception
- `InvalidArgumentException` for invalid inputs
- `InvalidConfigurationException` for configuration errors
- `RenderException` for rendering failures

#### Testing
- Comprehensive unit test suite (67%+ coverage)
- Integration tests for all chart types and formats
- Format compatibility tests (5 chart types × 3 formats = 15 combinations)
- Test fixtures with sample data for consistent testing

#### Examples
- Basic examples for each chart type
- Format comparison examples (PNG, WEBP, SVG)
- Custom color examples
- Grid line configuration examples
- Axis scaling examples
- Label and title examples
- Legend positioning examples
- Advanced styling example (all features combined)
- SVG export example (showcasing SVG advantages)

#### Documentation
- Complete README with feature overview and usage examples
- CONTRIBUTING guide with TDD workflow and quality gates
- PHPDoc documentation for all public APIs
- Inline code documentation

#### Quality Assurance
- PHPStan level 10 (strictest static analysis)
- PSR-12 code style compliance
- Strict types (`declare(strict_types=1)`) in all files
- PHP 8.1-8.5 compatibility
- Zero runtime dependencies (GD extension only for raster formats)
- Composer scripts for quality checks:
  - `composer test` - Run all tests
  - `composer analyse` - PHPStan analysis
  - `composer cs-fix` - PHP-CS-Fixer formatting
  - `composer ci` - Full quality gate check
  - `composer run-examples` - Run all examples
  - `composer verify-strict-types` - Verify strict types declarations

### Technical Details

#### Dependencies
- **Runtime**: None (zero dependencies)
- **Development**: PHPUnit 11.5+, PHPStan 2.1+, PHP-CS-Fixer 3.x

#### PHP Compatibility
- PHP 8.1, 8.2, 8.3, 8.4, 8.5
- Uses modern PHP features: enums, readonly properties, constructor property promotion

#### Performance
- Handles up to 10,000 data points efficiently
- Supports images up to 4000×4000 pixels
- SVG generation is pure PHP (no external dependencies)
- GD-based raster rendering for PNG/WEBP

### Design Decisions

- **Immutability**: DataPoint and DataSeries are immutable to prevent external modifications
- **Fluent Interface**: Chart configuration uses method chaining for ergonomic API
- **Strategy Pattern**: Chart type renderers use strategy pattern for extensibility
- **Value Objects**: Configuration objects are readonly for safety
- **Type Safety**: Full type declarations and PHPStan level 10 enforcement
- **TDD Approach**: Test-first development with comprehensive coverage

### Known Limitations

- Pie charts support single series only (by design)
- Radar charts require at least 3 data points (by design)
- GD extension required for PNG/WEBP formats (SVG works without dependencies)
- Data point labels may overlap in dense datasets (future enhancement)

---

## Release History

- **v0.1.0** (2026-01-15) - Initial release with full feature set

[Unreleased]: https://github.com/codryn/phpfastchart/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/codryn/phpfastchart/releases/tag/v0.1.0
