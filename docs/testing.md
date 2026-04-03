# Testing

This package contains both PHP and JavaScript test suites.

## Automated refactoring and coding standards

Run Rector:

```bash
composer rector
```

Run Easy Coding Standard with fixes:

```bash
composer ecs
```

## Dependency definition check

Verify runtime dependency declarations:

```bash
composer check-dependencies
```

## JavaScript asset test suite

Run the JavaScript tests for the jQuery assets:

```bash
npm test
```

This executes the Mocha suite under `tests/js/tests` against the default jQuery `3.7.1` runtime.

To verify dual compatibility explicitly, run:

```bash
npm run test:js:jquery3
npm run test:js:jquery4
```

## JavaScript linting

Run ESLint for the asset sources and JS tests:

```bash
npm run lint
```

## Mutation testing

Run mutation testing:

```bash
composer mutation
```

Run mutation testing with static analysis enabled:

```bash
composer mutation-static
```

## PHP test suite

Run the PHP unit and integration tests:

```bash
composer tests
```

This executes the PHPUnit suite defined in `phpunit.xml.dist`.

## Static analysis

Run PHPStan:

```bash
composer static
```

## Passing extra arguments

Composer scripts support forwarding additional arguments using `--`.

Examples:

```bash
composer tests -- --filter PjaxTest
composer static -- --memory-limit=512M
```

## Next steps

- 📚 [Installation Guide](installation.md)
- ⚙️ [Configuration Reference](configuration.md)
- 💡 [Usage Examples](examples.md)
