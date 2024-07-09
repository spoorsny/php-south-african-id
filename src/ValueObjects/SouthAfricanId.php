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

namespace Spoorsny\ValueObjects;

use DateTime;
use InvalidArgumentException;
use Stringable as BaseStringable;

use Faker\Calculator\Luhn;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * A self-validating value object encapsulating a South African
 * government-issued personal identification number.
 *
 * @see        {@link https://www.westerncape.gov.za/general-publication/decoding-your-south-african-id-number-0}
 * @see        {@link https://en.wikipedia.org/wiki/Value_object}
 * @see        {@link https://matthiasnoback.nl/2022/09/is-it-a-dto-or-a-value-object/#what%27s-a-value-object-and-how-do-you-recognize-it%3F}
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class SouthAfricanId implements BaseStringable
{
    /**
     * Value passed to constructor.
     */
    private ?string $rawValue;

    /**
     * Underlying value encapsulated by the value object.
     */
    private Stringable $value;

    /**
     * Creates a new instance of the value object.
     */
    public function __construct(?string $value)
    {
        $this->rawValue = $value;
        $this->value = Str::of($this->rawValue)->replace(' ', '');

        $this->assertIsNumeric();
        $this->assertCorrectLength();
        $this->assertStartsWithDate();
        $this->assertValidCitizenshipClassification();
        $this->assertValidCheckDigit();
    }

    /**
     * Casts the value object to a string.
     */
    public function __toString(): string
    {
        return $this->value()->value();
    }

    /**
     * Formatted version of underlying value encapsulated by the value object.
     */
    public function value(): Stringable
    {
        return $this->dateSegment()
            ->append(' ')
            ->append($this->genderSegment())
            ->append(' ')
            ->append($this->citizenshipSegment())
            ->append($this->raceSegment())
            ->append($this->checksumSegment());
    }

    /**
     * Ambiguous year in which the person was born in two-digit format, where
     * '84' could mean either '1984' or '1884', etc.
     */
    public function birthYear(): Stringable
    {
        return $this->dateSegment()->substr(0, 2);
    }

    /**
     * Month of the year, in which person was born in two-digit format, where
     * January is '01'.
     */
    public function birthMonth(): Stringable
    {
        return $this->dateSegment()->substr(2, 2);
    }

    /**
     * Day of the month, on which person was born in two-digit format, where the
     * first day is '01'.
     */
    public function birthDay(): Stringable
    {
        return $this->dateSegment()->substr(4, 2);
    }

    /**
     * Is the person identified by the number a female?
    */
    public function isFemale(): bool
    {
        return intval($this->genderSegment()->value()) < 5000;
    }

    /**
     * Is the person identified by the number a male?
    */
    public function isMale(): bool
    {
        return ! $this->isFemale();
    }

    /**
     * Is the person identified by the number a citizen of South Africa?
     */
    public function isCitizen(): bool
    {
        return $this->citizenshipSegment()->value() === '0';
    }

    /**
     * Is the person identified by the number a foreigner to whom permanent
     * residency has been granted?
     */
    public function isPermanentResident(): bool
    {
        return $this->citizenshipSegment()->value() === '1';
    }

    /**
     * Part of the identity number that indicates the person's birth date.
     */
    public function dateSegment(): Stringable
    {
        return $this->value->substr(0, 6);
    }

    /**
     * Part of the identity number that indicates the person's gender.
     */
    public function genderSegment(): Stringable
    {
        return $this->value->substr(6, 4);
    }

    /**
     * Part of the identity number that indicates the person's citizenship
     * classification.
     */
    public function citizenshipSegment(): Stringable
    {
        return $this->value->substr(10, 1);
    }

    /**
     * Part of the identity number that indicates the person's race.
     */
    public function raceSegment(): Stringable
    {
        return $this->value->substr(11, 1);
    }

    /**
     * Part of the identity number that validates the whole number.
     */
    public function checksumSegment(): Stringable
    {
        return $this->value->substr(12, 1);
    }

    /**
     * The identity number must contain only digits.
     */
    private function assertIsNumeric(): void
    {
        if (! $this->value->isMatch('/^\d+$/')) {
            throw new InvalidArgumentException("The value '{$this->rawValue}' is not numeric.");
        }
    }

    /**
     * The identity number must consist of the correct number of characters.
     */
    private function assertCorrectLength(): void
    {
        if ($this->value->length() < 13) {
            throw new InvalidArgumentException("The value '{$this->rawValue}' is shorter than 13 digits.");
        }

        if ($this->value->length() > 13) {
            throw new InvalidArgumentException("The value '{$this->rawValue}' is longer than 13 digits.");
        }
    }

    /**
     * The identity number must start with the person's date of birth.
     */
    private function assertStartsWithDate(): void
    {
        $dateFormat = 'ymd';

        $date = DateTime::createFromFormat("!$dateFormat", $this->dateSegment()->value());

        if (! $date || $date->format($dateFormat) !== $this->dateSegment()->value()) {
            throw new InvalidArgumentException(
                "The value '{$this->rawValue}' does not start with a date in the format 'yymmdd'."
            );
        }
    }

    /**
     * The identity number must indicate the citizenship classification of the
     * person.
     */
    private function assertValidCitizenshipClassification(): void
    {
        if (! in_array($this->citizenshipSegment()->value(), ['0', '1'])) {
            throw new InvalidArgumentException(
                "The value '{$this->rawValue}' does not have a valid citizenship classification."
            );
        }
    }

    /**
     * The identity number must end with a digit that validates itself.
     */
    private function assertValidCheckDigit(): void
    {
        $luhnIsValid = function (string $number) {
            $digits = array_map('intval', str_split($number));
            $reversedDigits = array_reverse($digits);

            $checksum = array_reduce(
                $reversedDigits,
                function ($sum, $digit) {
                    static $isEverySecondDigit = true;

                    $isEverySecondDigit = ! $isEverySecondDigit;

                    if ($isEverySecondDigit) {
                        $digit = array_sum(str_split($digit * 2));
                    }

                    return $sum + $digit;
                }
            );

            return $checksum % 10 === 0;
        };

        if (! $luhnIsValid($this->value)) {
            throw new InvalidArgumentException("The value '{$this->rawValue}' has an invalid checksum digit.");
        }
    }
}
