<?php

// This file is part of package spoorsny/south-african-id.
//
// Package spoorsny/south-african-id is free software: you can redistribute it
// and/or modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation, either version 3 of the License,
// or (at your option) any later version.
//
// Package spoorsny/south-african-id is distributed in the hope that it will be
// useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
// Public License for more details.
//
// You should have received a copy of the GNU General Public License along with
// package spoorsny/south-african-id. If not, see
// <https://www.gnu.org/licenses/>.

namespace Spoorsny\Tests;

/**
 * Test data for validating \Spoorsny\ValueObjects\SouthAfricanId.
 *
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 * @see        {@link https://docs.phpunit.de/en/11.2/writing-tests-for-phpunit.html#data-providers}
 */
class SouthAfricanIdDataProvider
{
    /**
     * @return array<arrray<string>>
     */
    public static function validSouthAfricanIdNumbers(): array
    {
        return [
            [' 2406203 71009 7  '],
            ['580717 9107 092'],
            ['7     903 1   11918098'],
            ['4711187297096'],
            ['55 03 25 23 02 09 4'],
            ['        0503190247091           '],
            ['3203140849099'],
            ['1610030121094'],
            ['790927557  9097'],
            ['3206236908                  083'],
        ];
    }
    /**
     * @return array<arrray<string>>
     */
    public static function nonnumericStrings(): array
    {
        return [
            ["123\0567"],
            ['123a567'],
            ['1b345678'],
            ['12345c7890'],
            ['123d5678901'],
            ['123456789e12'],
            ['1234f678901234'],
            ['1g3456789012345'],
            ['1234h67890123456'],
            ['12345678i01234567'],
            ['12345678901j345678'],
            ['1234567890k23456789'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function fewerThan13Digits(): array
    {
        return [
            ['1'],
            ['12'],
            ['123'],
            ['1234'],
            ['12345'],
            ['123456'],
            ['1234567'],
            ['12345678'],
            ['1234567890'],
            ['12345678901'],
            ['123456789012'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function moreThan13Digits(): array
    {
        return [
            ['12345678901234'],
            ['123456789012345'],
            ['1234567890123456'],
            ['12345678901234567'],
            ['123456789012345678'],
            ['1234567890123456789'],
            ['12345678901234567890'],
            ['123456789012345678901'],
            ['1234567890123456789012'],
            ['12345678901234567890123'],
            ['123456789012345678901234'],
            ['1234567890123456789012345'],
            ['12345678901234567890123456'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function doesNotStartWithDate(): array
    {
        return [
            ['971305 2879 088'],
            ['841183 1148 083'],
            ['800638 2539 096'],
            ['733329 1928 084'],
            ['710991 8954 099'],
            ['584201 5865 085'],
            ['591068 1661 089'],
            ['286629 7495 093'],
            ['550348 2681 097'],
            ['479104 6415 096'],
            ['221034 3900 084'],
            ['677727 2870 092'],
            ['910389 4750 099'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function invalidCitizenshipClassification(): array
    {
        return [
            ['971205 2879 388'],
            ['841113 1148 283'],
            ['800628 2539 596'],
            ['730329 1928 684'],
            ['710921 8954 799'],
            ['581201 5865 885'],
            ['591008 1661 989'],
            ['280629 7495 293'],
            ['550308 2681 497'],
            ['470104 6415 596'],
            ['221024 3900 684'],
            ['670727 2870 792'],
            ['910329 4750 899'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function invalidChecksumDigit(): array
    {
        return [
            ['971205 2879 087'],
            ['841113 1148 082'],
            ['800628 2539 095'],
            ['730329 1928 083'],
            ['710921 8954 098'],
            ['581201 5865 084'],
            ['591008 1661 088'],
            ['280629 7495 092'],
            ['550308 2681 096'],
            ['470104 6415 095'],
            ['221024 3900 083'],
            ['670727 2870 091'],
            ['910329 4750 098'],
        ];
    }
}
