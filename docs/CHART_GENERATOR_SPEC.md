# Feature Specification: 2D Chart Generator with Configurable Rendering

**Created**: 2026-01-12  
**Status**: Draft  
**Constitutional Compliance**: ✅ All principles applicable

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Generate Basic Chart Image (Priority: P1) 🎯 MVP

A developer needs to generate a simple chart image (PNG or WEBP) with data points and save it to disk.

**Why this priority**: Core chart generation capability - without this, no other features matter. This delivers immediate value: working chart output.

**Independent Test**: Create a chart instance, add data, set image format and size, generate to file. Verify file exists and contains valid image data.

**Acceptance Scenarios**:

1. **Given** chart data and configuration, **When** user calls `generate()` with PNG format, **Then** valid PNG file is created with correct dimensions
2. **Given** chart data and configuration, **When** user calls `generate()` with WEBP format, **Then** valid WEBP file is created with correct dimensions
3. **Given** invalid image format, **When** user calls `generate()`, **Then** descriptive exception is thrown with valid format list

---

### User Story 2 - Configure Visual Appearance (Priority: P1) 🎯 MVP

A developer needs to customize chart colors (background, lines, areas, axis) to match their application's design system.

**Why this priority**: Visual customization is essential for real-world usage. Users won't adopt a library that produces ugly or off-brand charts. This is part of MVP because basic charts with default colors aren't production-ready.

**Independent Test**: Create chart, set custom colors for background, lines, areas, and axis. Generate image and verify colors match configuration.

**Acceptance Scenarios**:

1. **Given** custom background color (including transparent), **When** chart is generated, **Then** image has specified background color
2. **Given** custom line colors for multiple data series, **When** chart is generated, **Then** each line uses its configured color
3. **Given** custom axis color, **When** chart is generated, **Then** axis lines and ticks use specified color
4. **Given** custom area fill colors, **When** chart with filled areas is generated, **Then** fills use specified colors

---

### User Story 3 - Add Grid Lines (Priority: P2)

A developer needs to add grid lines to make data values easier to read and estimate from the chart.

**Why this priority**: Grid lines significantly improve chart readability but aren't strictly required for a functioning chart. Can be added after basic chart generation works.

**Independent Test**: Create chart, configure grid lines (horizontal/vertical, spacing, visibility, line style). Generate and verify grid appears as configured.

**Acceptance Scenarios**:

1. **Given** horizontal grid lines enabled with spacing, **When** chart is generated, **Then** horizontal grid lines appear at correct intervals
2. **Given** vertical grid lines enabled with spacing, **When** chart is generated, **Then** vertical grid lines appear at correct intervals
3. **Given** grid lines disabled, **When** chart is generated, **Then** no grid lines appear
4. **Given** custom grid line color and width, **When** chart is generated, **Then** grid uses specified styling

---

### User Story 4 - Configure Axis Scaling (Priority: P2)

A developer needs to control axis ranges and scaling to properly display their data range and ensure comparability across multiple charts.

**Why this priority**: Auto-scaling works for many cases, but manual control is needed for data comparison and domain-specific requirements. Can be implemented after basic rendering.

**Independent Test**: Create chart, set explicit min/max for X and Y axes. Verify chart respects specified ranges.

**Acceptance Scenarios**:

1. **Given** explicit Y-axis min/max values, **When** chart is generated, **Then** Y-axis uses specified range
2. **Given** explicit X-axis min/max values, **When** chart is generated, **Then** X-axis uses specified range
3. **Given** no explicit range, **When** chart is generated, **Then** axes auto-scale to fit data
4. **Given** data outside specified range, **When** chart is generated, **Then** out-of-range data is clipped or throws exception

---

### User Story 5 - Add Labels and Text (Priority: P2)

A developer needs to add axis labels, data point labels, and title to make the chart self-documenting.

**Why this priority**: Labels improve comprehension but aren't required for visual data representation. Can be layered on after core rendering works.

**Independent Test**: Create chart, configure axis labels, title, and data labels. Verify text appears in correct positions with proper formatting.

**Acceptance Scenarios**:

1. **Given** X-axis label text, **When** chart is generated, **Then** label appears below X-axis
2. **Given** Y-axis label text, **When** chart is generated, **Then** label appears beside Y-axis
3. **Given** chart title, **When** chart is generated, **Then** title appears at top of chart
4. **Given** data point labels enabled, **When** chart is generated, **Then** values appear near data points

---

### User Story 6 - Add Legend (Priority: P3)

A developer needs a legend showing all data series with their colors to help users understand multi-series charts.

**Why this priority**: Legends are important for multi-series charts but not needed for single-series. This is enhancement-level functionality.

**Independent Test**: Create multi-series chart, configure legend with labels and colors. Verify legend displays all series correctly.

**Acceptance Scenarios**:

1. **Given** multiple data series with names, **When** legend is enabled, **Then** legend shows all series names with corresponding colors
2. **Given** legend position configured, **When** chart is generated, **Then** legend appears in specified position
3. **Given** legend disabled, **When** chart is generated, **Then** no legend appears
4. **Given** custom legend styling, **When** chart is generated, **Then** legend uses specified font, size, and colors

---

### User Story 7 - Support Multiple Chart Types (Priority: P1-P3) 🎯

A developer needs to generate different chart types (Bar, Line, Pie, Scatter, Radar) to visualize different kinds of data relationships.

**Why this priority**: Split by chart type complexity:
- **P1**: Line and Bar (most common, similar implementation)
- **P2**: Scatter (simple variation)
- **P3**: Pie and Radar (different geometry, less common)

**Independent Test**: For each chart type, create instance, add data, generate image. Verify chart type is rendered correctly.

**Acceptance Scenarios**:

1. **Given** line chart with data points, **When** generated, **Then** points connected with lines
2. **Given** bar chart with data values, **When** generated, **Then** bars displayed with correct heights
3. **Given** pie chart with data segments, **When** generated, **Then** circular chart with proportional segments
4. **Given** scatter chart with coordinate pairs, **When** generated, **Then** points plotted at correct positions
5. **Given** radar chart with multiple dimensions, **When** generated, **Then** polygon chart with axis for each dimension

---

### Edge Cases

- What happens when image dimensions are too small (e.g., 1x1 pixels)?
- How does system handle empty datasets (no data points)?
- What happens with extremely large datasets (10,000+ points)?
- How are negative values handled in charts that don't support them (e.g., Pie)?
- What happens when colors are specified in invalid formats?
- How does transparent background work with different image formats?
- What happens when data values are at extreme ranges (very large or very small)?
- How are axis labels handled when they would overlap?
- What happens when legend doesn't fit in available space?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: Library MUST generate PNG format images
- **FR-002**: Library MUST generate WEBP format images
- **FR-003**: Library MUST support configurable image width and height (in pixels)
- **FR-004**: Library MUST allow setting background color including full transparency
- **FR-005**: Library MUST allow setting axis line colors
- **FR-006**: Library MUST allow setting data line colors for each series
- **FR-007**: Library MUST allow setting area fill colors for each series
- **FR-008**: Library MUST support horizontal grid lines with configurable spacing
- **FR-009**: Library MUST support vertical grid lines with configurable spacing
- **FR-010**: Library MUST allow enabling/disabling grid lines independently per direction
- **FR-011**: Library MUST support configurable grid line styling (color, width)
- **FR-012**: Library MUST support axis labels (text for X and Y axes)
- **FR-013**: Library MUST support data point labels
- **FR-014**: Library MUST support configurable legend showing data series with colors
- **FR-015**: Library MUST support legend positioning
- **FR-016**: Library MUST support Bar chart type
- **FR-017**: Library MUST support Line chart type
- **FR-018**: Library MUST support Pie chart type
- **FR-019**: Library MUST support Scatter chart type
- **FR-020**: Library MUST support Radar chart type
- **FR-021**: Library MUST allow configurable X-axis scaling (min, max, auto)
- **FR-022**: Library MUST allow configurable Y-axis scaling (min, max, auto)
- **FR-023**: Library MUST validate input data and throw descriptive exceptions for invalid input
- **FR-024**: All public APIs MUST have complete PHPDoc documentation
- **FR-025**: All files MUST include `declare(strict_types=1)`
- **FR-026**: All code MUST pass PHPStan level 10 with strict rules
- **FR-027**: All code MUST be PSR-12 compliant

### Key Entities *(feature involves data)*

- **Chart**: Represents the chart instance with configuration and data. Attributes include chart type, dimensions, data series collection, styling configuration.

- **ChartType**: Enumeration of supported types (Bar, Line, Pie, Scatter, Radar). Each type has different rendering logic.

- **DataSeries**: Represents a single series of data points. Attributes include name/label, data points array, line color, fill color, visibility.

- **DataPoint**: Represents a single data point. Attributes include X value, Y value (optional for pie charts), label.

- **Axis**: Represents an axis (X or Y). Attributes include label, min value, max value, auto-scale flag, color, tick interval.

- **GridConfiguration**: Configuration for grid lines. Attributes include horizontal enabled, vertical enabled, spacing/interval, line color, line width.

- **Legend**: Configuration for chart legend. Attributes include enabled flag, position, series collection, font size, colors.

- **ColorConfiguration**: Holds all color settings. Attributes include background color, axis color, grid color, series-specific colors (mapping of series name to colors).

- **ImageConfiguration**: Output image settings. Attributes include width (pixels), height (pixels), format (PNG/WEBP), quality (for WEBP).

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Developers can generate a basic chart with default settings in fewer than 10 lines of code
- **SC-002**: Library can generate charts with up to 1,000 data points in under 1 second on standard hardware
- **SC-003**: Generated images are valid and can be opened in standard image viewers and browsers
- **SC-004**: All five chart types (Bar, Line, Pie, Scatter, Radar) are fully functional with complete API
- **SC-005**: Test coverage is at least 80% for all chart generation code
- **SC-006**: Zero PHPStan level 10 errors with strict rules enabled
- **SC-007**: Documentation includes working examples for each chart type
- **SC-008**: Library works identically on PHP 8.1, 8.2, 8.3, 8.4, and 8.5
- **SC-009**: Chart generation uses zero runtime dependencies beyond PHP's GD or Imagick extensions
- **SC-010**: All public methods have complete PHPDoc blocks with @param, @return, and @throws tags

## Assumptions *(documenting defaults)*

1. **Image Library**: Will use PHP's GD extension (standard with PHP) for image generation. GD supports PNG and WEBP formats required.

2. **Color Format**: Colors specified as hex strings ("#RRGGBB" or "#RRGGBBAA" for transparency) or named colors ("red", "blue", etc.).

3. **Coordinate System**: Standard Cartesian coordinate system with origin at bottom-left for Bar, Line, Scatter charts. Radar uses polar coordinates.

4. **Default Dimensions**: Default image size is 800x600 pixels if not specified.

5. **Font Handling**: Use GD's built-in fonts for labels and text. Custom font files not required for MVP.

6. **Grid Spacing**: When auto-calculated, grid lines aim for 5-10 divisions per axis.

7. **Data Format**: Data points provided as PHP arrays or objects implementing appropriate interfaces.

8. **Thread Safety**: Not required - PHP requests are typically single-threaded.

9. **Memory Limits**: Assume standard PHP memory limits (128MB+) sufficient for typical chart generation.

10. **Error Handling**: All errors throw typed exceptions with descriptive messages including context about what failed.

## Non-Requirements *(explicitly out of scope)*

- **3D Charts**: Only 2D charts supported in this specification
- **Animation**: Static images only, no animated charts
- **Interactive Features**: No hover effects, clickable elements, or JavaScript interactivity
- **SVG Output**: Only raster formats (PNG, WEBP), no vector formats
- **Real-time Updates**: Charts are generated on-demand, not updated in real-time
- **Chart Templates**: No predefined visual themes or templates
- **Data Import**: Library does not parse CSV, Excel, or database sources - data must be provided as PHP arrays
- **Statistical Analysis**: No built-in statistics, aggregations, or data transformations
- **Custom Shapes**: No support for custom geometric shapes beyond standard chart elements
- **Multi-page Charts**: Each chart generates a single image file

## Dependencies *(constitutional requirement: zero runtime dependencies)*

### Runtime Dependencies
- **PHP GD Extension**: Required for image generation (standard with PHP, not a Composer dependency)
- **PHP**: Version 8.1+ (constitutional requirement)

### Development Dependencies
- **PHPUnit**: 10.5+ for testing
- **PHPStan**: 2.1+ with strict rules for static analysis  
- **PHP-CS-Fixer**: 3.0+ for PSR-12 compliance
- **phpstan/phpstan-strict-rules**: 2.0+ for enhanced analysis

## Technical Constraints *(from constitution)*

1. **Type Safety**: All parameters and return types fully declared, strict types enabled
2. **Static Analysis**: Must pass PHPStan level 10 with strict rules (zero errors)
3. **Code Style**: PSR-12 compliant
4. **Testing**: TDD methodology - tests written before implementation
5. **Coverage**: Minimum 80% code coverage
6. **Documentation**: Complete PHPDoc for all public APIs
7. **PHP Compatibility**: Works on PHP 8.1, 8.2, 8.3, 8.4, 8.5
8. **CI**: Must pass tests on all supported PHP versions

## Constitutional Compliance Checklist

- ✅ **PHP 8.1-8.5 Compatibility**: Using only standard PHP features available in 8.1+
- ✅ **Zero Dependencies**: Only PHP GD extension required (standard extension)
- ✅ **PHPStan Level 10**: Design allows full type safety and static analysis
- ✅ **PSR-12**: Coding standards will be enforced
- ✅ **Strict Types**: All files will include `declare(strict_types=1)`
- ✅ **TDD**: Tests will be written before implementation
- ✅ **PHPDoc**: All public APIs will be fully documented
- ✅ **Test Coverage**: >= 80% target set as success criterion
- ✅ **CI Pipeline**: Tests on all PHP versions included in requirements
- ✅ **Documentation**: User-facing docs and examples included in success criteria

---

**Next Steps**: 
1. Review and approve this specification
2. Run `/speckit.plan` to create implementation plan with TDD workflow
3. Run `/speckit.tasks` to break down into atomic development tasks
4. Begin TDD implementation: Tests → Approval → Implementation
