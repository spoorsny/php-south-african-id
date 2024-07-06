![Repository Bannner](https://banners.beyondco.de/South%20African%20ID.png?theme=light&packageManager=composer+require&packageName=spoorsny%2Fsouth-african-id&pattern=curtain&style=style_1&description=A+self-validating+value+object+encapsulating+a+South+African+government-issued+personal+identification+number.&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Fwww.php.net%2Fimages%2Flogos%2Fnew-php-logo.svg&widths=500) <!-- markdownlint-disable-line first-line-h1 -->

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spoorsny/south-african-id.svg?style=flat-square)](https://packagist.org/packages/spoorsny/south-african-id)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spoorsny/php-south-african-id/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/spoorsny/php-south-african-id/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/spoorsny/php-south-african-id/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/spoorsny/php-south-african-id/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spoorsny/south-african-id.svg?style=flat-square)](https://packagist.org/packages/spoorsny/south-african-id)

# South African ID for PHP

A self-validating value object encapsulating a South African
government-issued personal [identification number](https://www.westerncape.gov.za/general-publication/decoding-your-south-african-id-number-0),
for PHP.

## Install

Use [Composer](https://getcomposer.org) to install the package.

```shell
composer require spoorsny/south-african-id
```

## Usage

The value object can be instantiated by passing a string to its constructor.

```php
use Spoorsny\ValueObjects\SouthAfricanId;

$idNumber = new SouthAfricanId('9308062469083');
```

If the string argument is not in the valid format, an `ArgumentException` with
a message indicative of the type of error, will be thrown.

Useful information encoded in the identity number can be extracted, for example:

```php
$idNumber->isFemale();
$idNumber->isMale();
$idNumber->isCitizen();
$idNumber->isPermanentResident();
$idNumber->birthMonth();
$idNumber->birthDay();
```

## License

Copyright &copy; 2024 Geoffrey Bernardo van Wyk [https://geoffreyvanwyk.dev](https://geoffreyvanwyk.dev)

This file is part of package spoorsny/south-african-id.

Package spoorsny/south-african-id is free software: you can redistribute it
and/or modify it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Package spoorsny/south-african-id is distributed in the hope that it will be
useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
Public License for more details.

You should have received a copy of the GNU General Public License along with
package spoorsny/south-african-id. If not, see <https://www.gnu.org/licenses/>.
