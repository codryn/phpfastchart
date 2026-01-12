<!--
SYNC IMPACT REPORT
==================
Version Change: INITIAL → 1.0.0
Reason: Initial constitution ratification with comprehensive quality standards

Principles Added:
- I. PHP Version Compatibility (PHP 8.1-8.5 support)
- II. Package Management (Composer 2 compatibility)
- III. Static Analysis Excellence (PHPStan 2.1, strict rules, level 10)
- IV. Coding Standards (PSR-12 compliance)
- V. Type Safety (Strict types declarations)
- VI. Test-Driven Development (TDD mandatory)
- VII. Documentation Excellence (Complete PHPDoc coverage)
- VIII. Test Coverage Requirements (PHPUnit >= 80% coverage)
- IX. Continuous Integration (CI pipeline for all PHP versions)
- X. Development Environment (Devcontainer with PHP 8.3)
- XI. User Documentation (Up-to-date user-facing documentation)

Templates Requiring Updates:
- ✅ plan-template.md - verified compatible with constitution gates
- ✅ spec-template.md - verified test-first methodology alignment
- ✅ tasks-template.md - verified TDD workflow integration

Follow-up TODOs: None - all principles fully specified

Generated: 2026-01-12
-->

# PHPFastChart Constitution

## Purpose

PHPFastChart exists to generate charts fast in PHP with minimal dependencies. This constitution defines the non-negotiable quality standards and development practices that ensure the library remains performant, maintainable, and reliable.

## Core Principles

### I. PHP Version Compatibility

PHPFastChart MUST maintain compatibility with PHP versions 8.1 through 8.5.

**Rationale**: Broad version support ensures the library can be adopted by projects across different PHP release cycles while leveraging modern PHP features. This range covers current LTS versions and recent stable releases.

**Requirements**:
- All code MUST work without modification on PHP 8.1, 8.2, 8.3, 8.4, and 8.5
- New PHP features from versions beyond 8.1 MUST be conditionally used or avoided
- Deprecation warnings MUST be addressed proactively before PHP version EOL

### II. Package Management

PHPFastChart MUST be a Composer 2-compatible package.

**Rationale**: Composer is the standard dependency manager for PHP. Composer 2 compatibility ensures fast installation and modern package management practices.

**Requirements**:
- Valid `composer.json` with proper PSR-4 autoloading
- Semantic versioning MUST be followed for all releases
- Zero runtime dependencies (development dependencies permitted)
- Package MUST be installable via `composer require codryn/phpfastchart`

### III. Static Analysis Excellence

PHPFastChart MUST pass PHPStan 2.1 analysis with strict rules enabled at level 10.

**Rationale**: PHPStan level 10 with strict rules provides the highest level of static analysis, catching type errors, dead code, and logical inconsistencies before runtime. This is non-negotiable for library code quality.

**Requirements**:
- Zero PHPStan errors at level 10 with strict rules
- PHPStan configuration MUST be committed to repository
- All code changes MUST pass PHPStan before merge
- Custom PHPStan rules MAY be added to enforce project-specific patterns

### IV. Coding Standards

PHPFastChart MUST follow PSR-12 coding standards.

**Rationale**: PSR-12 provides consistent, readable code that aligns with PHP community standards, making the codebase accessible to contributors and maintainers.

**Requirements**:
- All PHP code MUST pass PHP-CS-Fixer with PSR-12 configuration
- Automated formatting checks MUST be part of CI pipeline
- Code style violations MUST block pull request merges
- Exceptions to PSR-12 MUST be documented and justified in project configuration

### V. Type Safety

PHPFastChart MUST use strict types declarations in all PHP files.

**Rationale**: `declare(strict_types=1)` prevents silent type coercion bugs and ensures explicit type handling, critical for library reliability.

**Requirements**:
- Every PHP file MUST begin with `<?php declare(strict_types=1);`
- All function parameters MUST have type declarations
- All function return types MUST be declared
- Automated verification script MUST check strict types declarations
- Violations MUST block pull request merges

### VI. Test-Driven Development (NON-NEGOTIABLE)

PHPFastChart MUST follow strict test-driven development methodology.

**Rationale**: TDD ensures features are designed for testability, catches regressions early, and serves as living documentation. This is the foundation of code quality.

**Requirements**:
- Tests MUST be written BEFORE implementation
- New features MUST follow Red-Green-Refactor cycle:
  1. Write failing test
  2. User/reviewer approves test
  3. Verify test fails
  4. Implement minimum code to pass
  5. Refactor while keeping tests green
- No implementation code MUST be merged without corresponding tests
- Test failures MUST block all deployments

### VII. Documentation Excellence

PHPFastChart MUST maintain complete PHPDoc documentation for all code elements.

**Rationale**: Comprehensive documentation enables IDE autocomplete, generates API documentation, and serves as inline specification for maintainers and users.

**Requirements**:
- All classes MUST have PHPDoc blocks describing purpose
- All public methods MUST have PHPDoc with @param, @return, and @throws tags
- All class properties MUST have PHPDoc with @var tags
- Complex private methods SHOULD have PHPDoc for maintainability
- PHPDoc MUST be validated as part of static analysis
- Missing or incomplete PHPDoc MUST block pull request merges

### VIII. Test Coverage Requirements

PHPFastChart MUST maintain PHPUnit test coverage of 80% or higher.

**Rationale**: High test coverage ensures critical paths are verified and reduces regression risk. 80% balances thoroughness with pragmatism for edge cases.

**Requirements**:
- Overall line coverage MUST be >= 80%
- Coverage MUST be measured using PHPUnit with Xdebug or PCOV
- Coverage reports MUST be generated in CI pipeline
- Decrease in coverage MUST block pull request merges
- Untested code MUST be explicitly justified or removed

### IX. Continuous Integration

PHPFastChart MUST run CI pipeline testing on all supported PHP versions.

**Rationale**: Testing across PHP 8.1, 8.2, 8.3, 8.4, and 8.5 ensures compatibility promises are met and prevents version-specific regressions.

**Requirements**:
- CI MUST test on PHP 8.1, 8.2, 8.3, 8.4, and 8.5
- All tests MUST pass on all PHP versions before merge
- CI MUST run: tests with coverage, PHPStan analysis, PHP-CS-Fixer checks, strict types verification
- CI failure MUST block merges
- CI MUST run on all pull requests and main branch commits

### X. Development Environment

PHPFastChart MUST be developed inside a devcontainer with PHP 8.3.

**Rationale**: Consistent development environment eliminates "works on my machine" issues and ensures all developers use the same tooling and PHP version.

**Requirements**:
- Devcontainer configuration MUST be maintained in `.devcontainer/`
- Devcontainer MUST use PHP 8.3 as the base development version
- All required development tools MUST be pre-installed in devcontainer
- Devcontainer MUST include Composer, PHPUnit, PHPStan, PHP-CS-Fixer, and Xdebug
- Documentation MUST include devcontainer setup instructions

### XI. User Documentation

PHPFastChart MUST maintain up-to-date user-facing documentation.

**Rationale**: Documentation is the interface between the library and its users. Outdated documentation erodes trust and increases support burden.

**Requirements**:
- README.md MUST include: installation, quick start, feature overview, links to detailed docs
- Each public API MUST have usage examples
- Examples directory MUST contain working code samples
- Documentation MUST be updated in the same pull request as code changes
- Stale documentation MUST be flagged during code review

## Quality Gates

All code changes MUST pass the following gates before merge:

1. **Static Analysis Gate**: PHPStan level 10 with strict rules (zero errors)
2. **Code Style Gate**: PSR-12 compliance via PHP-CS-Fixer (zero violations)
3. **Type Safety Gate**: Strict types declarations verified in all files
4. **Test Gate**: All tests passing on PHP 8.1, 8.2, 8.3, 8.4, 8.5
5. **Coverage Gate**: Test coverage >= 80% maintained
6. **Documentation Gate**: PHPDoc complete for all public APIs
7. **TDD Gate**: Tests exist for all new functionality

## Development Workflow

### Feature Implementation Process

1. **Specification**: Document feature requirements and acceptance criteria
2. **Test Design**: Write failing tests covering happy path and edge cases
3. **Test Review**: Tests reviewed and approved before implementation
4. **Implementation**: Write minimum code to pass tests
5. **Refactoring**: Improve design while maintaining green tests
6. **Documentation**: Update PHPDoc, README, and examples
7. **Quality Verification**: Pass all quality gates
8. **Review**: Peer review for constitution compliance
9. **Merge**: Automated CI validation before merge to main

### Prohibited Practices

- Merging failing tests with "TODO: fix later" comments
- Skipping tests with `markTestSkipped()` without justification and tracking issue
- Disabling PHPStan errors with `@phpstan-ignore` without documented exception approval
- Committing code without strict types declaration
- Reducing test coverage without explicit architectural justification
- Adding runtime dependencies without constitutional amendment

## Governance

### Authority

This constitution supersedes all other development practices, style preferences, and informal agreements. When in doubt, the constitution is the final authority.

### Amendments

Constitutional amendments require:

1. Documented rationale for change
2. Impact analysis on existing code and practices
3. Migration plan for affected code
4. Approval from project maintainers
5. Version bump following semantic versioning:
   - MAJOR: Backward-incompatible principle changes or removals
   - MINOR: New principles or materially expanded guidance
   - PATCH: Clarifications, wording improvements, typo fixes

### Compliance

- All pull requests MUST be reviewed for constitutional compliance
- Deviations from principles MUST be explicitly justified and documented
- Technical debt violating principles MUST be tracked and prioritized for resolution
- CI pipeline enforces automated compliance checks

### Exceptions

Exceptions to constitutional principles are permitted only when:

1. Technical impossibility is demonstrated (not mere difficulty)
2. Exception is documented in code comments and project documentation
3. Exception is time-bound with remediation plan
4. Exception is approved by project maintainers

**Version**: 1.0.0 | **Ratified**: 2026-01-12 | **Last Amended**: 2026-01-12
