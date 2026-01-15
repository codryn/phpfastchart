# PHPFastChart Development Guidelines

## Active Technologies

- PHP 8.1-8.5 (dev environment: PHP 8.3) + gd (zero runtime dependencies - development only: PHPUnit, PHPStan, PHP-CS-Fixer)

## Project Structure

```text
doc/
examples/
src/
tests/
```

## Code Style

- All code must be compatible to PHP 8.1-8.5
- Follow standard conventions
- dev environment: PHP 8.3 (devcontainer)
- PSR-12 coding standards using PHP-CS-Fixer.
- Strict Types: Ensure `declare(strict_types=1);` is present in all PHP files.
- Line Endings: Use LF (`\n`) for all files. Use `composer fix-line-endings` to correct line endings.
- Unit Tests: Use PHPUnit for unit testing. Place tests in the `tests/` directory, mirroring the `src/` structure.
- Static Analysis: Use PHPStan at level 10 (strict) for static code analysis.


## Commands
```
composer fix-line-endings     # Fix all line endings to LF
composer run-examples         # Run all example scripts
composer test                 # Run all tests  
composer analyse              # Run PHPStan static analysis  
composer cs-check             # Check code style with PHP-CS-Fixer
composer cs-fix               # Run PHP-CS-Fixer for code style compliance
composer verify-strict-types  # Verify strict types declarations
composer ci                   # Run all quality checks and tests  
```

## Documentation

- README.md: Main project documentation
- LICENSE: License information

TODO: Add more detailed documentation files as needed.