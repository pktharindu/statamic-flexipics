# Contributing to Statamic Flexipics

Thank you for considering contributing to Statamic Flexipics! This document outlines the guidelines for contributing to the project.

## Code of Conduct

By participating in this project, you agree to abide by its Code of Conduct. Please be respectful and considerate of others.

## How to Contribute

### Reporting Issues

If you find a bug or have a suggestion for improving Statamic Flexipics, please first check the [issue tracker](https://github.com/pktharindu/statamic-flexipics/issues) to see if it has already been reported. If not, feel free to open a new issue with the following information:

- A clear and descriptive title
- A detailed description of the issue or suggestion
- Steps to reproduce the bug (if applicable)
- Expected behavior and actual behavior
- Screenshots (if applicable)
- Environment information (PHP version, Statamic version, etc.)

### Pull Requests

We welcome pull requests! To contribute code:

1. Fork the repository
2. Create a new branch for your feature or bug fix
3. Write your code, following the coding standards
4. Add or update tests as necessary
5. Ensure all tests pass by running `composer test`
6. Submit a pull request

### Development Setup

To set up the project for development:

1. Clone your fork of the repository
2. Install dependencies with `composer install`
3. Run tests with `composer test`

## Coding Standards

This project follows the PSR-12 coding standard and uses PHP 8.2 features. Please ensure your code adheres to these standards.

We use [Duster](https://github.com/tighten/duster) for code style enforcement. You can check your code with:

```bash
composer lint
```

And automatically fix style issues with:

```bash
composer lint-fix
```

## Testing

All new features and bug fixes should include tests. We use [Pest](https://pestphp.com/) for testing.

Run the test suite with:

```bash
composer utest
```

## Documentation

If your changes require documentation updates, please include them in your pull request.

## Release Process

The maintainers will handle the release process, including version bumping and tagging.

## Questions?

If you have any questions about contributing, feel free to open an issue for clarification.

Thank you for your contributions!
