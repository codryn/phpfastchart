# Implementation Plan: [FEATURE]

**Branch**: `[###-feature-name]` | **Date**: [DATE] | **Spec**: [link]
**Input**: Feature specification from `/specs/[###-feature-name]/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

[Extract from feature spec: primary requirement + technical approach from research]

## Technical Context

<!--
  ACTION REQUIRED: Replace the content in this section with the technical details
  for the project. The structure here is presented in advisory capacity to guide
  the iteration process.
-->

**Language/Version**: PHP 8.1-8.5 (dev environment: PHP 8.3)
**Primary Dependencies**: None (zero runtime dependencies - development only: PHPUnit, PHPStan, PHP-CS-Fixer)
**Storage**: [if applicable, e.g., files, JSON, XML or N/A]
**Testing**: PHPUnit 10.5+ with >= 80% coverage requirement
**Target Platform**: [e.g., CLI, Web/CGI, Library only or NEEDS CLARIFICATION]
**Project Type**: PHP Library (single project structure)
**Performance Goals**: [domain-specific, e.g., <100ms for 1000 data points or NEEDS CLARIFICATION]
**Constraints**: Zero runtime dependencies, PSR-12, PHPStan level 10 with strict rules
**Scale/Scope**: [domain-specific, e.g., support datasets up to X size, Y charts or NEEDS CLARIFICATION]

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

All features MUST comply with PHPFastChart constitution principles:

- [ ] **PHP Compatibility**: Works on PHP 8.1, 8.2, 8.3, 8.4, 8.5
- [ ] **Zero Dependencies**: No new runtime dependencies (development dependencies OK)
- [ ] **PHPStan Level 10**: Passes strict rules with zero errors
- [ ] **PSR-12**: Code style compliant
- [ ] **Strict Types**: `declare(strict_types=1)` in all files
- [ ] **TDD**: Tests written and approved before implementation
- [ ] **PHPDoc**: Complete documentation for all public APIs
- [ ] **Test Coverage**: >= 80% coverage maintained or improved
- [ ] **CI Pipeline**: Tests pass on all PHP versions
- [ ] **Documentation**: User-facing docs updated

**If ANY gate cannot be met**: Document in Complexity Tracking section below

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
<!--
  ACTION REQUIRED: Replace the placeholder tree below with the concrete layout
  for this feature. Delete unused options and expand the chosen structure with
  real paths. The delivered plan must not include Option labels.
-->

```text
# [REMOVE IF UNUSED] Option 1: PHP Library (DEFAULT for PHPFastChart)
src/
├── [Feature]/           # Feature namespace
│   ├── [Entity].php     # Data models/entities
│   ├── [Service].php    # Business logic
│   └── [Exception].php  # Custom exceptions
└── [Shared]/            # Shared utilities

tests/
├── Unit/
│   └── [Feature]/
│       └── [Entity]Test.php
├── Integration/
│   └── [Feature]/
│       └── [Service]Test.php
└── Fixtures/            # Test data

examples/
└── [feature]-example.php

# [REMOVE IF UNUSED] Option 2: Web application (when "frontend" + "backend" detected)
backend/
├── src/
│   ├── Controller/
│   ├── Service/
│   └── Entity/
└── tests/

frontend/
├── src/
│   ├── components/
│   ├── pages/
│   └── services/
└── tests/
```

**Structure Decision**: [Document the selected structure and reference the real
directories captured above]

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| [e.g., 4th project] | [current need] | [why 3 projects insufficient] |
| [e.g., Repository pattern] | [specific problem] | [why direct DB access insufficient] |
