# Phase 6 Grid Lines - Incomplete Implementation

**Status**: Partially implemented  
**Date**: 2026-01-13

## ✅ Completed Components

### 1. GridConfiguration (src/Configuration/GridConfiguration.php)
- Immutable readonly value object
- All `with*` methods working correctly
- Properties: enabled, showHorizontalLines, showVerticalLines, color, lineWidth, spacing
- **Status**: ✅ Complete and tested (8 tests passing)

### 2. MathUtil (src/Util/MathUtil.php)
- `calculateGridSpacing()` method for automatic grid spacing
- Returns "nice numbers" (1, 2, 5, 10, 20, 50, etc.)
- **Status**: ✅ Complete and tested (6 tests passing)

### 3. Chart Class Updates (src/Chart/Chart.php)
- Added `private GridConfiguration $gridConfig` property
- Added methods:
  - `enableGrid(bool $enabled = true): self`
  - `setGridColor(string $color): self`
  - `setGridSpacing(float $spacing): self`
  - `setGridLineWidth(float $width): self`
- **Status**: ✅ Complete

### 4. Test Files
- GridConfigurationTest.php: 8 tests, all passing ✅
- MathUtilTest.php: 6 tests, all passing ✅
- GridRenderingTest.php: 6 integration tests written ⚠️ (failing due to rendering issue)

### 5. Example File
- examples/grid-lines.php: Complete example showing grid usage ✅

## ❌ Known Issues

### SvgRenderer.php File Synchronization Problem
The `src/Renderer/SvgRenderer.php` file has file synchronization issues between the editor and disk. The following changes were attempted but not properly saved:

#### Required Changes:
1. **Update render() method signature** to accept GridConfiguration as 4th parameter:
   ```php
   public function render(
       ChartType $type,
       array $dataSeries,
       ColorConfiguration $colorConfig,
       GridConfiguration $gridConfig  // <-- Add this
   ): string
   ```

2. **Update renderLineChart() signature**:
   ```php
   private function renderLineChart(
       array $dataSeries,
       ColorConfiguration $colorConfig,
       GridConfiguration $gridConfig  // <-- Add this
   ): string
   ```

3. **Update renderBarChart() signature**:
   ```php
   private function renderBarChart(
       array $dataSeries,
       ColorConfiguration $colorConfig,
       GridConfiguration $gridConfig  // <-- Add this
   ): string
   ```

4. **Add renderGrid() method** (complete implementation in git history or see below)

5. **Fix renderLineChart() to use global bounds**:
   - Calculate bounds for ALL points from ALL series once
   - Use these global bounds for both grid rendering AND path rendering
   - Remove per-series bounds calculation to ensure grid aligns with data

6. **Fix renderBarChart() similarly**:
   - Calculate global bounds once
   - Render grid with global bounds
   - Render bars using same global bounds

### Current Error
```
PHPStan: Method Codryn\PHPFastChart\Renderer\SvgRenderer::render() invoked with 4 parameters, 3 required.
```

### Test Failures
- 3 integration tests failing in GridRenderingTest.php
- Tests expect grid lines in SVG output but they're not being rendered
- Issue: SVG output only contains axes, no grid lines or data paths

## 🔧 How to Fix

### Option 1: Manual File Edit
Open `src/Renderer/SvgRenderer.php` in an editor and apply the changes listed above.

### Option 2: Use Git History
Check the conversation summary for the complete renderGrid() implementation that was written but not saved.

### Option 3: Complete Rewrite
The renderGrid() method should:
- Accept GridConfiguration and chart bounds
- Calculate grid spacing (use custom spacing or auto-calculate with MathUtil)
- Render horizontal lines at regular Y intervals
- Render vertical lines at regular X intervals
- Use RGB color format: `stroke="rgb(r,g,b)"` with `opacity="0.7"`
- Only render lines between min/max bounds (not at edges)

## 📊 Test Results

**Current State**:
- Total tests: 74
- Passing: 69 (GridConfiguration: 8, MathUtil: 6, others: 55)
- Failing: 3 (GridRenderingTest integration tests)
- Errors: 2 (due to method signature mismatch)

**PHPStan**: 1 error (method parameter count)
**Coverage**: ~93% (will improve once rendering is fixed)

## 📝 Tasks Remaining

- [ ] T105 - Fix grid rendering in SvgRenderer.php
- [ ] T106 - Verify all US3 tests PASS (GREEN phase)
- [ ] T107 - Refactor grid code (REFACTOR phase)
- [ ] T108 - Run PHPStan level 10 - fix errors
- [ ] T109 - Run PHP-CS-Fixer - fix violations
- [ ] T110 - Verify coverage >= 80%
- [ ] T111 - Verify grid-lines-example.php works correctly

## 🎯 Expected Outcome

Once fixed, the grid lines should:
- Appear in both line and bar charts when enabled
- Use custom or auto-calculated spacing
- Align perfectly with chart data
- Render in specified colors with 0.7 opacity
- Support horizontal-only, vertical-only, or both

## 📚 Reference Implementation

The renderGrid() method structure:
```php
private function renderGrid(
    GridConfiguration $gridConfig,
    int $marginLeft,
    int $marginTop,
    int $chartWidth,
    int $chartHeight,
    float $minX,
    float $maxX,
    float $minY,
    float $maxY,
    float $rangeX,
    float $rangeY
): string {
    // Implementation here
}
```
