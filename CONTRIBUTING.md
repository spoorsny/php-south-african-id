# Contributing

## Library Organization

The library's directory structure is set up to conform to the
[Standard PHP Skeleton][standard-php-skeleton] specification.

The Composer script names are according to the
[Composer Script Names][composer-script-names] specification.

## Prerequisites

To contribute to this project, you will need:

- PHP 8.3+
- Composer

For installing additional versions of PHP on the Ubuntu operating system, read
[How to run multiple PHP versions on Ubuntu][multiple-php] on Digital Ocean's community
portal. The article covers PHP 7.0 and 7.2 on Ubuntu 18.04, but it is also
applicable to other PHP and Ubuntu versions.

Composer can just be installed with from the Ubuntu repositories. If the version
in the Ubuntu repositories is too old, you can run:

```bash
composer self-update
```

Also consult [Composer's documentation][composer] for more information.

## Obtain Source Code

Clone the source code from GitHub:

```bash
git clone https://github.com/spoorsny/php-south-african-id
```

Install the Composer dependencies:

```bash
cd php-south-african-id
composer install
```

## Code Style

The library follows the [PSR 12][psr12] coding standard. To automatically update the
source code to meet this standard, issue command:

```bash
composer cs-fix
```

## Testing & Code Coverage

The library has tests located in the **tests** that are based on [PHPUnit][phpunit]. To
run the tests, from the root of the repository, issue command:

```bash
composer test
```

To also see what percentage of the source code gets executed when the tests are
run, issue command:

```bash
composer test-coverage
```

Contributions are only accepted if they include tests (where necessary) and the
code coverage is 100%.

The test data were generated using [Faker][faker] with the `en_ZA` locale and the
`idNumber()` method.

## Branch Management

The Git branch that contains the canonical version of the library is named
`master`. On GitHub, this branch is protected, meaning that commits cannot be
added directly to the branch. Commits can only be added via a merged pull
request.

To contribute to the source code, follow the [fork & pull request workflow][fork].

If a pull request is meant to resolve a GitHub issue, add "Resolves #3" to the
description of the pull request (replacing "3" with the relevant issue number).
That way the issue is automatically closed when the pull request is merged.

## Commit Message Convention

The commit messages of this library follows the [Conventional Commit][convcom]
convention. Also see previous commits in the repository for how it is used. The
idea is to simplify the creation and automation of changelogs.

At the moment, the library's [Releases][releases] page on GitHub serves as the changelog,
but automating the update of a `CHANGELOG.md` file is on the today list.

If a commit is related to a GitHub issue, add "Relates to #3" to the footer of
the commit message (replacing "3" with the relevant issue number).

## GitHub Actions Workflows

When a pull request is made, a _Continuous Integration_ GitHub Actions workflow
is automatically started, which:

- lints the source code, fixing the code style and automatically creating a new commit
- runs the tests
- checks the code coverage to be 100%

All three jobs must be successful before the pull request can be merged. The
workflow is run again on the `master` branch after the pull request is merged.

## Publishing to Packagist & Semantic Versioning

The package has been registerd on [Packagist][pack] under username `@geoffreyvanwyk`.
Packagist automatically crawls the repository for updates and registers a new
version based on tags.

This library follows [Semantic Versioning][semver]. A new version of the package is
indicated with a Git tag that starts with a `v`. A release is then also created
on GitHub based on that tag, and GitHub's built-in release notes generator is
used.

[multiple-php]: https://www.digitalocean.com/community/tutorials/how-to-run-multiple-php-versions-on-one-server-using-apache-and-php-fpm-on-ubuntu-18-04
[composer]: https://getcomposer.org
[phpunit]: https://phpunit.de
[faker]: https://fakerphp.org
[standard-php-skeleton]: https://github.com/php-pds/skeleton
[composer-script-names]: https://github.com/php-pds/composer-script-names/tree/1.0.0
[psr12]: https://www.php-fig.org/psr/psr-12/
[fork]: https://www.atlassian.com/git/tutorials/comparing-workflows/forking-workflow
[convcom]: https://www.conventionalcommits.org/en/v1.0.0/
[releases]: https://github.com/spoorsny/php-south-african-id/releases
[pack]: https://packagist.org/spoorsny/south-african-id
[semver]: https://semver.org
