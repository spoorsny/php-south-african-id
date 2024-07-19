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

use InvalidArgumentException;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use Spoorsny\Tests\SouthAfricanIdDataProvider;
use Spoorsny\ValueObjects\SouthAfricanId;

/**
 * Unit test for \Spoorsny\ValueObjects\SouthAfricanId.
 *
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
#[CoversClass(SouthAfricanId::class)]
final class SouthAfricanIdTest extends TestCase
{
    /**
     * A South African identity number encodes a person's birthday.
     */
    #[Test]
    public function it_encodes_date_of_birth(): void
    {
        $idNumber = new SouthAfricanId('4608162219097');

        $this->assertEquals('46', $idNumber->birthYear());
        $this->assertEquals('08', $idNumber->birthMonth());
        $this->assertEquals('16', $idNumber->birthDay());
    }

    /**
     * A South African identity number can tell if a person is male.
     */
    #[Test]
    public function it_can_tell_if_person_is_male(): void
    {
        $idNumber = new SouthAfricanId('8202277454090');

        $this->assertTrue($idNumber->isMale());
    }

    /**
     * A South African identity number can tell if a person is female.
     */
    #[Test]
    public function it_can_tell_if_person_is_female(): void
    {
        $idNumber = new SouthAfricanId('4608162219097');

        $this->assertTrue($idNumber->isFemale());
    }

    /**
     * A South African identity number can tell if a person is a citizen.
     */
    #[Test]
    public function it_can_tell_if_person_is_a_citizen(): void
    {
        $idNumber = new SouthAfricanId('6510224960080');

        $this->assertTrue($idNumber->isCitizen());
    }

    /**
     * A South African identity number can tell if a person is a permanent resident.
     */
    #[Test]
    public function it_can_tell_if_person_is_a_permanent_resident(): void
    {
        $idNumber = new SouthAfricanId('5503252302193');

        $this->assertTrue($idNumber->isPermanentResident());
    }

    /**
     * A South African identity number has a standard formatting.
     */
    #[Test]
    public function it_formats_the_number(): void
    {
        $idNumber = new SouthAfricanId('5503252302193');

        $this->assertEquals('550325 2302 193', strval($idNumber));
    }

    /**
     * A South African identity number value object can be used as a string.
     */
    #[Test]
    public function it_can_be_used_as_a_string(): void
    {
        $idNumber = new SouthAfricanId('5503252302193');

        $this->assertEquals('550325 2302 193', strval($idNumber));

        $this->expectOutputString('550325 2302 193');

        echo $idNumber;
    }

    /**
     * A South African identity number value object can be instantiated from
     * another instance.
     */
    #[Test]
    public function it_can_be_instantiated_from_another_instance(): void
    {
        $idNumber1 = new SouthAfricanId('5503252302193');
        $idNumber2 = new SouthAfricanId($idNumber1);

        $this->assertEquals('550325 2302 193', strval($idNumber2));
    }

    /**
     * A South African identity number is numeric.
     */
    #[DataProviderExternal(SouthAfricanIdDataProvider::class, methodName: 'nonnumericStrings')]
    #[Test]
    public function it_is_numeric(string $southAfricanId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The value '{$southAfricanId}' is not numeric.");

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African identity number contains at least 13 digits.
     */
    #[DataProviderExternal(SouthAfricanIdDataProvider::class, methodName: 'fewerThan13Digits')]
    #[Test]
    public function it_contains_at_least_13_digits(string $southAfricanId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The value '{$southAfricanId}' is shorter than 13 digits.");

        new SouthAfricanId($southAfricanId);

    }

    /**
    * A South African identity number contains at most 13 digits.
    */
    #[DataProviderExternal(SouthAfricanIdDataProvider::class, methodName: 'moreThan13Digits')]
    #[Test]
    public function it_contains_at_most_13_digits(string $southAfricanId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The value '{$southAfricanId}' is longer than 13 digits.");

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African identity number starts with a date in 'yymmdd' format.
     */
    #[DataProviderExternal(SouthAfricanIdDataProvider::class, methodName: 'doesNotStartWithDate')]
    #[Test]
    public function it_starts_with_date(string $southAfricanId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The value '{$southAfricanId}' does not start with a date in the format 'yymmdd'.");

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African identity number must correctly classify citizenship.
     */
    #[DataProviderExternal(SouthAfricanIdDataProvider::class, methodName: 'invalidCitizenshipClassification')]
    #[Test]
    public function it_correctly_classifies_citizenship(string $southAfricanId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The value '{$southAfricanId}' does not have a valid citizenship classification.");

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African identity number must end with a valid checksum digit.
     */
    #[DataProviderExternal(SouthAfricanIdDataProvider::class, methodName: 'invalidChecksumDigit')]
    #[Test]
    public function it_has_correct_checksum_digit(string $southAfricanId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The value '{$southAfricanId}' has an invalid checksum digit.");

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African identity number can be checked for equality with another number.
     */
    #[Test]
    public function it_can_be_checked_for_equality(): void
    {
        $idNumber1 = new SouthAfricanId('4608162219097');
        $idNumber2 = new SouthAfricanId('4608162219097');
        $idNumber3 = new SouthAfricanId('8202277454090');

        $this->assertTrue($idNumber1->equals($idNumber2));
        $this->assertFalse($idNumber1->equals($idNumber3));
    }
}
