# Contributing to the PKD Server Reference Code

Thanks for taking the time to read this contributing guidelines document. You rock!

This repository only contains the reference implementation for the server-side component of the Public Key Directory.

## Reporting Bugs

Please open an issue for any bugs you find. Please include as much detail as possible, including steps to reproduce the 
bug.

## Suggesting Features

Please open an issue to suggest new features for the project.

## Reporting Security Vulnerabilities

Believe it or not, **open an issue**.

There is no security vulnerability so severe that we don't want immediate full disclosure.

## Pull Requests

Pull requests are welcome. Before submitting a pull request, please ensure that you have:

1. Opened an issue to discuss the change.
2. Added tests for your change. More on that below.
3. Run the test suite to ensure that your change does not break anything.
4. Updated the documentation to reflect your change.

### Testing Your Changes

We currently use three layers of software assurance.

* PHPUnit -- unit testing
* Psalm -- static analysis and ensuring strict type-safety for our code
* Infection -- mutation testing (runs on tags, not commits)

#### Running PHPUnit

Make sure you don't pass `--no-dev` when installing the dependencies with Composer. Then, simply run:

```terminal
vendor/bin/phpunit --strict-coverage
```

#### Running Psalm

By default, we don't require Psalm in our composer.json file. This is a temporary measure while we contend with PHP 8.5
compatibility issues. For now, run the following commands:

```terminal
# Require psalm
composer require --dev vimeo/psalm:^6

# Undo changes to composer.json
git checkout -- composer.json

# Run psalm
vendor/bin/psalm 
```

#### Running Infection

Like psalm, we don't currently have infection defined in `require-dev`. This will be amended in the near future.

```terminal
# Install the pcov extesion
pecl install pcov

# You may also need to add "extension=pcov.so" to your php.ini

# Install infection
composer require --dev infection/infection

# Run infection
vendor/bin/infection
```

Infection is significantly slower than the other testing methodologies, but it catches untested code.

## Specification Changes

This project is a reference implementation of the [Public Key Directory specification](https://github.com/fedi-e2ee/public-key-directory-specification).
Any changes to the behavior of the code that is encapsulated by the specification must be done in tandem with a change 
to the specification itself.

If you are interested in making a change that would affect the specification, please open an issue in the 
[specification repository](https://github.com/fedi-e2ee/public-key-directory-specification) to discuss your idea.
