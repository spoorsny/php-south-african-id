![Repository Bannner](https://banners.beyondco.de/South%20African%20ID.png?theme=light&packageManager=composer+require&packageName=spoorsny%2Fsouth-african-id&pattern=circuitBoard&style=style_1&description=A+self-validating+value+object+encapsulating+a+South+African+government-issued+personal+identification+number.&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Fwww.php.net%2Fimages%2Flogos%2Fnew-php-logo.svg&widths=500) <!-- markdownlint-disable-line first-line-h1 -->

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spoorsny/south-african-id.svg?style=flat-square)](https://packagist.org/packages/spoorsny/south-african-id)
[![Total Downloads](https://img.shields.io/packagist/dt/spoorsny/south-african-id.svg?style=flat-square)](https://packagist.org/packages/spoorsny/south-african-id)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spoorsny/php-south-african-id/continuous-integration.yml?branch=master&label=tests&style=flat-square)](https://github.com/spoorsny/php-south-african-id/actions?query=workflow%3Acontinuous-integration+branch%3Amaster)
[![PHPUnit Code Coverage](https://github.com/spoorsny/php-south-african-id/blob/image-data/coverage.svg)](https://github.com/spoorsny/php-south-african-id/actions?query=workflow%3Acontinuous-integration+branch%3Amaster)

# South African ID for PHP

A self-validating value object encapsulating a South African
government-issued personal [identification number](https://www.westerncape.gov.za/general-publication/decoding-your-south-african-id-number-0),
for PHP.

A South African government-issued identity number consists of **13** digits and the following segments:

- **birth date:** the first **6** digits represent the person's date of birth
  in the format _yymmdd_.
- **gender:** the next **4** digits indicate that the person is male if the
  value is **5000** or above; otherwise, female.
- **citizenship:** the next single digit shows the person's citizenship
  status: **0** for a citizen, **1** for a permanent resident.
- **race:** the next single digit was historically used to indicate the person's race.
- **check digit:** the last digit is used to validate the entire number, to
  protect against typing errors. It is calculated with the
  [Luhn Algorithm](https://en.wikipedia.org/wiki/Luhn_algorithm).

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

If the string argument is not in the valid format, an
`InvalidArgumentException` with a message indicative of the type of error, will
be thrown.

The value object can used in places where strings are used, because it
implements the [`Stringable`](https://php.net/Stringable) interface. For
example, a value object can be instantiated by passing another instance to its
constructor.

```php
$idNumber1 = new SouthAfricanId('4608162219097');
$idNumber2 = new SouthAfricanId($idNumber1);
```

> [!NOTE]
> The value object always formats the identity number with a single
> space between the date segment and the gender segment, and a single space
> between the gender segment and the citizenship segment.

It can be used with the `strval()` function and `echo` statement.

```php
$idNumber = new SouthAfricanId('4608162219097');
strval($idNumber); // Evaluates to '460816 2219 097'.
echo $idNumber;    // Prints '460816 2219 097'.
```

Different instances of the class can be checked for equality with the `equals()`
method.

```php
$idNumber1 = new SouthAfricanId('4608162219097');
$idNumber2 = new SouthAfricanId('4608162219097');
$idNumber3 = new SouthAfricanId('8202277454090');

$idNumber1->equals($idNumber2); // true
$idNumber1->equals($idNumber3); // false
```

Even though the identity number starts with the person's birth date, it cannot
be used to compare whether one person is older than another. This is due to the
century portion being missing from the date.

Useful information encoded in the identity number can be extracted, for example:

```php
$idNumber->birthMonth();
$idNumber->birthDay();

$idNumber->isFemale();
$idNumber->isMale();

$idNumber->isCitizen();
$idNumber->isPermanentResident();
```

The different segments of the identity number can be extracted from the value object as follows:

```php
$idNumber->dateSegment();
$idNumber->genderSegment();
$idNumber->citizenshipSegment();
$idNumber->raceSegment();
$idNumber->checksumSegment();
```

## Contributing

To contribute to the package, see the [Contributing Guide](CONTRIBUTING.md).

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

For a copy of the license, see the [LICENSE](LICENSE) file in this repository.
