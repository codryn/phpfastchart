# Tasks: 2D Chart Generator with Configurable Rendering

**Feature**: Chart Generation
**Input**: Design documents from `/specs/001-generate-charts/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/

**TDD Workflow**: All tasks follow Red-Green-Refactor cycle:
1. Write test (RED) → 2. Verify test fails → 3. Implement (GREEN) → 4. Refactor → 5. Quality gates

## Format: `- [ ] [ID] [P?] [Story?] Description with file path`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: User story this task belongs to (US1, US2, etc.)
- Include exact file paths in descriptions

## Path Conventions

- **PHP Library**: `src/`, `tests/`, `examples/` at repository root
- **Test structure**: `tests/Unit/`, `tests/Integration/`, `tests/Fixtures/`
- **Namespaces**: `Codryn\PHPFastChart\*` following PSR-4 autoloading

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure per PHPFastChart constitution

- [X] T001 Create directory structure: src/{Chart,Data,Configuration,Renderer,Exception,Util}
- [X] T002 Create directory structure: tests/{Unit,Integration,Fixtures}
- [X] T003 Create directory structure: examples/
- [X] T004 [P] Update composer.json with PSR-4 autoloading for Codryn\PHPFastChart namespace
- [X] T005 [P] Verify PHPStan configuration at phpstan.neon (level 10, strict rules)
- [X] T006 [P] Verify PHP-CS-Fixer configuration for PSR-12
- [X] T007 [P] Verify PHPUnit configuration at phpunit.xml

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [X] T008 [P] Write test for ChartException base class in tests/Unit/Exception/ChartExceptionTest.php
- [X] T009 [P] Create ChartException.php with strict types and PHPDoc in src/Exception/
- [X] T010 [P] Write test for InvalidArgumentException in tests/Unit/Exception/InvalidArgumentExceptionTest.php
- [X] T011 [P] Create InvalidArgumentException.php in src/Exception/
- [X] T012 [P] Write test for InvalidConfigurationException in tests/Unit/Exception/InvalidConfigurationExceptionTest.php
- [X] T013 [P] Create InvalidConfigurationException.php in src/Exception/
- [X] T014 [P] Write test for RenderException in tests/Unit/Exception/RenderExceptionTest.php
- [X] T015 [P] Create RenderException.php in src/Exception/
- [X] T016 [P] Write test for ChartType enum in tests/Unit/Chart/ChartTypeTest.php
- [X] T017 [P] Create ChartType.php enum with all 5 types in src/Chart/
- [X] T018 [P] Write test for ImageFormat enum in tests/Unit/Configuration/ImageFormatTest.php
- [X] T019 [P] Create ImageFormat.php enum (PNG, WEBP, SVG) in src/Configuration/
- [X] T020 [P] Write test for ColorParser utility in tests/Unit/Util/ColorParserTest.php
- [X] T021 [P] Create ColorParser.php with hex and named color parsing in src/Util/
- [X] T022 [P] Write test for Validator utility in tests/Unit/Util/ValidatorTest.php
- [X] T023 [P] Create Validator.php with dimension, color, range validation in src/Util/
- [X] T024 Run PHPStan and PHP-CS-Fixer on foundational code, fix all issues
- [X] T025 Verify all foundational tests pass and coverage >= 80%

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - Generate Basic Chart Image (Priority: P1) 🎯 MVP

**Goal**: Generate PNG, WEBP, and SVG chart files with basic line chart rendering

**Independent Test**: Create chart, add simple data, generate to file in each format

### Tests for User Story 1 (TDD: Write tests FIRST)

- [X] T026 [P] [US1] Write test for DataPoint creation and immutability in tests/Unit/Data/DataPointTest.php
- [X] T027 [P] [US1] Write test for DataSeries creation with validation in tests/Unit/Data/DataSeriesTest.php  
- [ ] T028 [P] [US1] Write test for DataCollection management in tests/Unit/Data/DataCollectionTest.php
- [ ] T029 [P] [US1] Write test for Chart construction and size validation in tests/Unit/Chart/ChartTest.php
- [ ] T030 [P] [US1] Write test for RendererInterface contract in tests/Unit/Renderer/RendererInterfaceTest.php
- [ ] T031 [P] [US1] Write test for RasterRenderer (GD) in tests/Unit/Renderer/RasterRendererTest.php
- [ ] T032 [P] [US1] Write test for SvgRenderer in tests/Unit/Renderer/SvgRendererTest.php
- [X] T033 [US1] Write integration test for PNG generation in tests/Integration/ChartGenerationTest.php
- [ ] T034 [US1] Write integration test for WEBP generation in tests/Integration/ChartGenerationTest.php
- [X] T035 [US1] Write integration test for SVG generation in tests/Integration/ChartGenerationTest.php
- [X] T036 [US1] Verify all US1 tests FAIL (RED phase confirmed)

### Implementation for User Story 1

- [X] T037 [P] [US1] Implement DataPoint.php with readonly properties and strict types in src/Data/
- [X] T038 [P] [US1] Implement DataSeries.php with immutable deep copy in src/Data/
- [ ] T039 [P] [US1] Implement DataCollection.php with add/get methods in src/Data/
- [X] T040 [US1] Implement Chart.php with fluent interface methods in src/Chart/
- [ ] T041 [US1] Implement RendererInterface.php contract in src/Renderer/
- [ ] T042 [US1] Implement RasterRenderer.php using GD for PNG/WEBP in src/Renderer/
- [X] T043 [US1] Implement SvgRenderer.php with pure PHP XML generation in src/Renderer/
- [X] T044 [US1] Implement basic LineChartRenderer.php in src/Renderer/ChartRenderer/
- [X] T045 [US1] Implement Chart->generate() method with file output
- [X] T046 [US1] Implement Chart->render() method returning string
- [X] T047 [US1] Run all US1 tests - verify they PASS (GREEN phase)
- [X] T048 [US1] Refactor US1 code for clarity while keeping tests green (REFACTOR phase)
- [X] T049 [US1] Run PHPStan level 10 - fix any errors
- [X] T050 [US1] Run PHP-CS-Fixer - fix PSR-12 violations
- [ ] T051 [US1] Verify coverage >= 80% for US1 code
- [X] T052 [US1] Create basic-line-chart.php example in examples/
- [X] T053 [US1] Update README.md with basic usage example

**Checkpoint**: User Story 1 MVP complete - basic SVG line chart generation working

---

## Phase 4: User Story 2 - Configure Visual Appearance (Priority: P1) 🎯 MVP

**Goal**: Customize colors for background, lines, areas, and axes

**Independent Test**: Generate chart with custom colors, verify colors match

### Tests for User Story 2

- [X] T054 [P] [US2] Write test for ColorConfiguration class in tests/Unit/Configuration/ColorConfigurationTest.php
- [X] T055 [P] [US2] Write test for background color rendering in tests/Integration/ColorRenderingTest.php
- [X] T056 [P] [US2] Write test for axis color rendering in tests/Integration/ColorRenderingTest.php
- [X] T057 [P] [US2] Write test for line color rendering in tests/Integration/ColorRenderingTest.php
- [X] T058 [P] [US2] Write test for fill color rendering in tests/Integration/ColorRenderingTest.php
- [X] T059 [P] [US2] Write test for transparent background in tests/Integration/ColorRenderingTest.php
- [X] T060 [US2] Verify all US2 tests FAIL (RED phase)

### Implementation for User Story 2

- [X] T061 [P] [US2] Implement ColorConfiguration.php with fluent setters in src/Configuration/
- [X] T062 [US2] Add color support to Chart.php (setBackgroundColor, setAxisColor methods)
- [X] T063 [US2] Add background rendering to RasterRenderer.php
- [X] T064 [US2] Add background rendering to SvgRenderer.php
- [X] T065 [US2] Add axis color support to renderers
- [X] T066 [US2] Add line color support from DataSeries to LineChartRenderer
- [ ] T067 [US2] Add fill color support for area charts
- [ ] T068 [US2] Add transparent background support for PNG
- [X] T069 [US2] Run all US2 tests - verify PASS (GREEN phase)
- [X] T070 [US2] Refactor color code (REFACTOR phase)
- [X] T071 [US2] Run PHPStan level 10 - fix errors
- [X] T072 [US2] Run PHP-CS-Fixer - fix violations
- [X] T073 [US2] Verify coverage >= 80%
- [X] T074 [US2] Create custom-colors-example.php in examples/

**Checkpoint**: User Story 2 complete - full color customization working

---

## Phase 5: User Story 7a - Support Bar and Line Charts (Priority: P1) 🎯 MVP

**Goal**: Implement Bar and Line chart types (most common chart types)

**Independent Test**: Generate bar chart and line chart, verify correct rendering

### Tests for User Story 7a

- [X] T075 [P] [US7] Write test for ChartRendererInterface in tests/Unit/Renderer/ChartRenderer/ChartRendererInterfaceTest.php
- [X] T076 [P] [US7] Write test for BarChartRenderer in tests/Unit/Renderer/ChartRenderer/BarChartRendererTest.php
- [X] T077 [P] [US7] Write test for LineChartRenderer in tests/Unit/Renderer/ChartRenderer/LineChartRendererTest.php
- [X] T078 [US7] Write integration test for bar chart in tests/Integration/ChartTypeTest.php
- [X] T079 [US7] Write integration test for line chart in tests/Integration/ChartTypeTest.php
- [X] T080 [US7] Verify all US7a tests FAIL (RED phase)

### Implementation for User Story 7a

- [ ] T081 [P] [US7] Implement ChartRendererInterface.php in src/Renderer/ChartRenderer/
- [X] T082 [P] [US7] Implement BarChartRenderer.php with bar drawing logic in src/Renderer/ChartRenderer/
- [X] T083 [US7] Complete LineChartRenderer.php (started in US1) in src/Renderer/ChartRenderer/
- [X] T084 [US7] Add chart type routing in Chart.php to select renderer
- [ ] T085 [US7] Integrate BarChartRenderer into RasterRenderer
- [X] T086 [US7] Integrate BarChartRenderer into SvgRenderer
- [X] T087 [US7] Run all US7a tests - verify PASS (GREEN phase)
- [X] T088 [US7] Refactor chart renderer code (REFACTOR phase)
- [X] T089 [US7] Run PHPStan level 10 - fix errors
- [X] T090 [US7] Run PHP-CS-Fixer - fix violations
- [X] T091 [US7] Verify coverage >= 80%
- [X] T092 [US7] Create bar-chart-example.php in examples/
- [X] T093 [US7] Create line-chart-example.php in examples/

**Checkpoint**: Bar and Line charts fully functional - MVP core complete

---

## Phase 6: User Story 3 - Add Grid Lines (Priority: P2)

**Goal**: Configurable horizontal and vertical grid lines with auto-spacing

**Independent Test**: Enable grid, verify lines appear at correct intervals

### Tests for User Story 3

- [X] T094 [P] [US3] Write test for GridConfiguration in tests/Unit/Configuration/GridConfigurationTest.php
- [X] T095 [P] [US3] Write test for MathUtil.calculateGridSpacing in tests/Unit/Util/MathUtilTest.php
- [X] T096 [US3] Write integration test for horizontal grid in tests/Integration/GridRenderingTest.php
- [X] T097 [US3] Write integration test for vertical grid in tests/Integration/GridRenderingTest.php
- [X] T098 [US3] Write integration test for custom grid spacing in tests/Integration/GridRenderingTest.php
- [X] T099 [US3] Verify all US3 tests FAIL (RED phase)

### Implementation for User Story 3

- [X] T100 [P] [US3] Implement GridConfiguration.php as readonly value object in src/Configuration/
- [X] T101 [P] [US3] Implement MathUtil.php with grid spacing calculation in src/Util/
- [X] T102 [US3] Add enableGrid() method to Chart.php
- [X] T103 [US3] Add setGridStyle() method to Chart.php
- [ ] T104 [US3] Add grid rendering to RasterRenderer.php (deferred - PNG/WEBP)
- [X] T105 [US3] Add grid rendering to SvgRenderer.php
- [X] T106 [US3] Run all US3 tests - verify PASS (GREEN phase)
- [X] T107 [US3] Refactor grid code (REFACTOR phase)
- [X] T108 [US3] Run PHPStan level 10 - fix errors
- [X] T109 [US3] Run PHP-CS-Fixer - fix violations
- [X] T110 [US3] Verify coverage >= 80%
- [X] T111 [US3] Create grid-lines-example.php in examples/

**Checkpoint**: Grid lines fully functional

---

## Phase 7: User Story 4 - Configure Axis Scaling (Priority: P2)

**Goal**: Manual and automatic axis scaling with data validation

**Independent Test**: Set axis range, verify chart respects bounds

### Tests for User Story 4

- [X] T112 [P] [US4] Write test for AxisConfiguration in tests/Unit/Configuration/AxisConfigurationTest.php
- [X] T113 [P] [US4] Write test for AxisClipMode enum in tests/Unit/Configuration/AxisClipModeTest.php
- [X] T114 [P] [US4] Write test for MathUtil.dataToPixel coordinate transform in tests/Unit/Util/MathUtilTest.php
- [X] T115 [P] [US4] Write test for MathUtil.calculateNiceNumber in tests/Unit/Util/MathUtilTest.php
- [X] T116 [US4] Write integration test for manual Y-axis range in tests/Integration/AxisScalingTest.php
- [X] T117 [US4] Write integration test for auto-scaling in tests/Integration/AxisScalingTest.php
- [X] T118 [US4] Write integration test for out-of-range exception in tests/Integration/AxisScalingTest.php
- [X] T119 [US4] Write integration test for clip mode in tests/Integration/AxisScalingTest.php
- [X] T120 [US4] Verify all US4 tests FAIL (RED phase)

### Implementation for User Story 4

- [X] T121 [P] [US4] Implement AxisConfiguration.php with range and clip mode in src/Configuration/
- [X] T122 [P] [US4] Implement AxisClipMode.php enum in src/Configuration/
- [X] T123 [US4] Add coordinate transformation methods to MathUtil.php
- [X] T124 [US4] Add auto-scaling logic to MathUtil.php
- [X] T125 [US4] Add setAxisRange() method to Chart.php
- [X] T126 [US4] Add setAxisClipMode() method to Chart.php
- [X] T127 [US4] Implement axis scaling in chart renderers
- [X] T128 [US4] Implement out-of-range validation in Chart.generate()
- [X] T129 [US4] Run all US4 tests - verify PASS (GREEN phase)
- [X] T130 [US4] Refactor axis code (REFACTOR phase)
- [X] T131 [US4] Run PHPStan level 10 - fix errors
- [X] T132 [US4] Run PHP-CS-Fixer - fix violations
- [X] T133 [US4] Verify coverage >= 80%
- [X] T134 [US4] Create axis-scaling-example.php in examples/

**Checkpoint**: Axis scaling fully functional

---

## Phase 8: User Story 5 - Add Labels and Text (Priority: P2)

**Goal**: Axis labels, titles, and data point labels

**Independent Test**: Add labels, verify they appear in correct positions

### Tests for User Story 5

- [X] T135 [US5] Write test for axis labels in Chart in tests/Unit/Chart/ChartTest.php (extend existing)
- [X] T136 [US5] Write integration test for X-axis label in tests/Integration/LabelRenderingTest.php
- [X] T137 [US5] Write integration test for Y-axis label in tests/Integration/LabelRenderingTest.php
- [X] T138 [US5] Write integration test for chart title in tests/Integration/LabelRenderingTest.php
- [X] T139 [US5] Write integration test for data point labels in tests/Integration/LabelRenderingTest.php
- [X] T140 [US5] Verify all US5 tests FAIL (RED phase)

### Implementation for User Story 5

- [X] T141 [US5] Add setAxisLabel() method to Chart.php
- [X] T142 [US5] Add setTitle() method to Chart.php
- [X] T143 [US5] Add enableDataLabels() method to Chart.php
- [ ] T144 [US5] Implement axis label rendering in RasterRenderer.php (deferred - PNG/WEBP)
- [X] T145 [US5] Implement axis label rendering in SvgRenderer.php
- [X] T146 [US5] Implement title rendering in renderers
- [X] T147 [US5] Implement data point label rendering in chart renderers
- [X] T148 [US5] Run all US5 tests - verify PASS (GREEN phase)
- [X] T149 [US5] Refactor label code (REFACTOR phase)
- [X] T150 [US5] Run PHPStan level 10 - fix errors
- [X] T151 [US5] Run PHP-CS-Fixer - fix violations
- [X] T152 [US5] Verify coverage >= 80%
- [X] T153 [US5] Create labels-example.php in examples/

- [ ] T149 [US5] Refactor label code (REFACTOR phase)
- [ ] T150 [US5] Run PHPStan level 10 - fix errors
- [ ] T151 [US5] Run PHP-CS-Fixer - fix violations
- [ ] T152 [US5] Verify coverage >= 80%
- [ ] T153 [US5] Create labels-example.php in examples/

**Checkpoint**: Labels and text fully functional

---

## Phase 9: User Story 7b - Support Scatter Charts (Priority: P2)

**Goal**: Implement Scatter plot chart type

**Independent Test**: Create scatter chart, verify points plotted correctly

### Tests for User Story 7b

- [ ] T154 [P] [US7] Write test for ScatterChartRenderer in tests/Unit/Renderer/ChartRenderer/ScatterChartRendererTest.php
- [ ] T155 [US7] Write integration test for scatter chart in tests/Integration/ChartTypeTest.php
- [ ] T156 [US7] Verify all US7b tests FAIL (RED phase)

### Implementation for User Story 7b

- [ ] T157 [P] [US7] Implement ScatterChartRenderer.php in src/Renderer/ChartRenderer/
- [ ] T158 [US7] Integrate ScatterChartRenderer into Chart.php routing
- [ ] T159 [US7] Run all US7b tests - verify PASS (GREEN phase)
- [ ] T160 [US7] Refactor scatter code (REFACTOR phase)
- [ ] T161 [US7] Run PHPStan level 10 - fix errors
- [ ] T162 [US7] Run PHP-CS-Fixer - fix violations
- [ ] T163 [US7] Verify coverage >= 80%
- [ ] T164 [US7] Create scatter-chart-example.php in examples/

**Checkpoint**: Scatter charts fully functional

---

## Phase 10: User Story 6 - Add Legend (Priority: P3)

**Goal**: Configurable legend showing all data series

**Independent Test**: Enable legend, verify all series appear with colors

### Tests for User Story 6

- [ ] T165 [P] [US6] Write test for LegendConfiguration in tests/Unit/Configuration/LegendConfigurationTest.php
- [ ] T166 [P] [US6] Write test for LegendPosition enum in tests/Unit/Configuration/LegendPositionTest.php
- [ ] T167 [US6] Write integration test for legend rendering in tests/Integration/LegendRenderingTest.php
- [ ] T168 [US6] Write integration test for legend positions in tests/Integration/LegendRenderingTest.php
- [ ] T169 [US6] Verify all US6 tests FAIL (RED phase)

### Implementation for User Story 6

- [ ] T170 [P] [US6] Implement LegendConfiguration.php as readonly value object in src/Configuration/
- [ ] T171 [P] [US6] Implement LegendPosition.php enum in src/Configuration/
- [ ] T172 [US6] Add enableLegend() method to Chart.php
- [ ] T173 [US6] Add disableLegend() method to Chart.php
- [ ] T174 [US6] Implement legend rendering in RasterRenderer.php
- [ ] T175 [US6] Implement legend rendering in SvgRenderer.php
- [ ] T176 [US6] Run all US6 tests - verify PASS (GREEN phase)
- [ ] T177 [US6] Refactor legend code (REFACTOR phase)
- [ ] T178 [US6] Run PHPStan level 10 - fix errors
- [ ] T179 [US6] Run PHP-CS-Fixer - fix violations
- [ ] T180 [US6] Verify coverage >= 80%
- [ ] T181 [US6] Create legend-example.php in examples/

**Checkpoint**: Legend fully functional

---

## Phase 11: User Story 7c - Support Pie and Radar Charts (Priority: P3)

**Goal**: Implement Pie and Radar chart types (complex geometry)

**Independent Test**: Generate pie and radar charts, verify correct rendering

### Tests for User Story 7c

- [ ] T182 [P] [US7] Write test for PieChartRenderer in tests/Unit/Renderer/ChartRenderer/PieChartRendererTest.php
- [ ] T183 [P] [US7] Write test for RadarChartRenderer in tests/Unit/Renderer/ChartRenderer/RadarChartRendererTest.php
- [ ] T184 [P] [US7] Write test for MathUtil.polarToCartesian in tests/Unit/Util/MathUtilTest.php
- [ ] T185 [US7] Write integration test for pie chart in tests/Integration/ChartTypeTest.php
- [ ] T186 [US7] Write integration test for radar chart in tests/Integration/ChartTypeTest.php
- [ ] T187 [US7] Verify all US7c tests FAIL (RED phase)

### Implementation for User Story 7c

- [ ] T188 [P] [US7] Implement PieChartRenderer.php with arc/slice drawing in src/Renderer/ChartRenderer/
- [ ] T189 [P] [US7] Implement RadarChartRenderer.php with polygon drawing in src/Renderer/ChartRenderer/
- [ ] T190 [US7] Add polar coordinate conversion to MathUtil.php
- [ ] T191 [US7] Integrate PieChartRenderer into Chart.php routing
- [ ] T192 [US7] Integrate RadarChartRenderer into Chart.php routing
- [ ] T193 [US7] Add pie chart single-series validation
- [ ] T194 [US7] Add radar chart minimum dimensions validation
- [ ] T195 [US7] Run all US7c tests - verify PASS (GREEN phase)
- [ ] T196 [US7] Refactor pie/radar code (REFACTOR phase)
- [ ] T197 [US7] Run PHPStan level 10 - fix errors
- [ ] T198 [US7] Run PHP-CS-Fixer - fix violations
- [ ] T199 [US7] Verify coverage >= 80%
- [ ] T200 [US7] Create pie-chart-example.php in examples/
- [ ] T201 [US7] Create radar-chart-example.php in examples/

**Checkpoint**: All 5 chart types fully functional

---

## Phase 12: Polish & Cross-Cutting Concerns

**Purpose**: Final quality improvements, documentation, and comprehensive testing

- [ ] T202 [P] Create test fixtures for consistent test data in tests/Fixtures/SampleData.php
- [ ] T203 [P] Create format compatibility test in tests/Integration/FormatCompatibilityTest.php
- [ ] T204 [P] Test all chart types with all 3 formats (15 combinations)
- [ ] T205 [P] Create advanced-styling-example.php demonstrating all features in examples/
- [ ] T206 [P] Create svg-export-example.php in examples/
- [ ] T207 [P] Update main README.md with complete feature list and usage
- [ ] T208 [P] Create CONTRIBUTING.md with TDD workflow and quality gates
- [ ] T209 [P] Create CHANGELOG.md with v1.0.0 feature list
- [ ] T210 Run full test suite on PHP 8.1, 8.2, 8.3, 8.4, 8.5
- [ ] T211 Generate coverage report - verify >= 80% overall
- [ ] T212 Run PHPStan level 10 on entire codebase - zero errors required
- [ ] T213 Run PHP-CS-Fixer on entire codebase - zero violations required
- [ ] T214 Run strict types verification script - 100% coverage required
- [ ] T215 Performance test: 1,000 data points in <1 second
- [ ] T216 Memory test: Verify 4000×4000 images work within PHP limits
- [ ] T217 Documentation review: Verify all PHPDoc complete
- [ ] T218 Example validation: Run all example files, verify output
- [ ] T219 Final integration test: Complex multi-series chart with all features
- [ ] T220 Tag release v1.0.0

**Checkpoint**: Feature complete, all quality gates passed, ready for release

---

## Summary

**Total Tasks**: 220
**MVP Tasks** (Phase 1-5): 93 tasks
**P2 Features** (Phase 6-9): 68 tasks  
**P3 Features** (Phase 10-11): 38 tasks
**Polish** (Phase 12): 21 tasks

### Task Distribution by User Story

- **US1** (Basic Generation): 27 tasks → MVP core
- **US2** (Colors): 21 tasks → MVP styling
- **US3** (Grid Lines): 18 tasks → P2
- **US4** (Axis Scaling): 23 tasks → P2
- **US5** (Labels): 19 tasks → P2
- **US6** (Legend): 17 tasks → P3
- **US7a** (Bar/Line): 19 tasks → MVP charts
- **US7b** (Scatter): 11 tasks → P2
- **US7c** (Pie/Radar): 20 tasks → P3
- **Foundation**: 18 tasks
- **Setup**: 7 tasks
- **Polish**: 21 tasks

### Parallel Execution Opportunities

Each user story (US1-US7) is independently testable after Foundation phase:
- Foundation (T008-T025) must complete first
- US1 blocks nothing (pure addition)
- US2-US7 can work in parallel after US1
- All [P] marked tasks within a phase are parallelizable

### MVP Delivery (First 93 Tasks)

Completing phases 1-5 delivers:
- ✅ Basic chart generation (PNG, WEBP, SVG)
- ✅ Full color customization
- ✅ Bar and Line charts functional
- ✅ Complete TDD workflow established
- ✅ 80%+ test coverage
- ✅ All constitutional gates passing

### Implementation Strategy

1. **Weeks 1-2**: Foundation + US1 (basic generation)
2. **Week 3**: US2 + US7a (colors + bar/line charts) = **MVP COMPLETE**
3. **Week 4**: US3 + US4 (grid + scaling)
4. **Week 5**: US5 + US7b (labels + scatter)
5. **Week 6**: US6 + US7c (legend + pie/radar)
6. **Week 7**: Polish, examples, documentation

**Estimated Timeline**: 6-7 weeks for complete feature with all 220 tasks

---

**Ready for Implementation**: Begin with Phase 1 (Setup) tasks T001-T007
