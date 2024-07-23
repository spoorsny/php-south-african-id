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
use ReflectionClass;
use Stringable;
use stdClass;

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
final class SouthAfricanId implements Stringable
{
    /**  Underlying value encapsulated by the value object. */
    public readonly string $value;

    /** Exact number of characters allowed in an identity number, excluding whitespace. */
    public const SIZE = 13;

    /** Format of the date with which the identity number starts. */
    public const DATE_FORMAT = 'ymd';

    /**
     * Number in the gender segment of the identity number above-which the person
     * is male (inclusive) and below-which the person is female.
     */
    public const GENDER_CUTOFF = 5000;

    /** Digit that indicates that a person is a South African citizen. */
    public const CITIZEN = '0';

    /** Digit that indicates that a person is a permanent resident in South Africa. */
    public const PERMANENT_RESIDENT = '1';

    /** @throws  \InvalidArgumentException */
    public function __construct(string $value)
    {
        $this->value = $value;

        foreach (self::validationRules() as $methodName) {
            $rule = self::$methodName();

            if (($rule->fails)()) {
                throw new InvalidArgumentException($rule->message);
            }
        }
    }

    /**
     * Prepare the underlying value for validation, and manipulation.
     */
    private function value(): string
    {
        return preg_replace('/\s/', '', $this->value);
    }

    public function __toString(): string
    {
        return $this->dateSegment()
            . ' '
            . $this->genderSegment()
            . ' '
            . $this->citizenshipSegment()
            . $this->raceSegment()
            . $this->checksumSegment();
    }

    /**
     * Ambiguous year in which the person was born in two-digit format, where
     * '84' could mean either '1984' or '1884', etc.
     */
    public function birthYear(): string
    {
        return substr($this->dateSegment(), 0, 2);
    }

    /**
     * Month of the year, in which person was born in two-digit format, where
     * January is '01'.
     */
    public function birthMonth(): string
    {
        return substr($this->dateSegment(), 2, 2);
    }

    /**
     * Day of the month, on which person was born in two-digit format, where the
     * first day is '01'.
     */
    public function birthDay(): string
    {
        return substr($this->dateSegment(), 4, 2);
    }

    /**
     * Is the person identified by the number a female?
    */
    public function isFemale(): bool
    {
        return intval($this->genderSegment()) < self::GENDER_CUTOFF;
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
        return $this->citizenshipSegment() === self::CITIZEN;
    }

    /**
     * Is the person identified by the number a foreigner to whom permanent
     * residency has been granted?
     */
    public function isPermanentResident(): bool
    {
        return $this->citizenshipSegment() === self::PERMANENT_RESIDENT;
    }

    /**
     * Part of the identity number that indicates the person's birth date.
     */
    public function dateSegment(): string
    {
        return substr($this->value(), 0, 6);
    }

    /**
     * Part of the identity number that indicates the person's gender.
     */
    public function genderSegment(): string
    {
        return substr($this->value(), 6, 4);
    }

    /**
     * Part of the identity number that indicates the person's citizenship
     * classification.
     */
    public function citizenshipSegment(): string
    {
        return substr($this->value(), 10, 1);
    }

    /**
     * Part of the identity number that indicates the person's race.
     */
    public function raceSegment(): string
    {
        return substr($this->value(), 11, 1);
    }

    /** Part of the identity number that validates the whole number. */
    public function checksumSegment(): string
    {
        return substr($this->value(), 12, 1);
    }

    /** The names of all the methods in this class, which end with "Rule". */
    private function validationRules(): array
    {
        $methods = array_filter(
            (new ReflectionClass(__CLASS__))->getMethods(),
            fn ($method) => str_ends_with($method->name, 'Rule')
        );

        $methodNames = array_map(fn ($method) => $method->name, $methods);

        return array_values($methodNames);
    }

    /** The identity number must contain only digits. */
    private function numericRule(): object
    {
        $rule = new stdClass();

        /** @var string Error message sent when the value fails the rule. */
        $rule->message = "The value '{$this->value}' contains nonnumeric characters.";

        /** @var callback Returns false, if the rule invalidates the value; otherwise, true. */
        $rule->fails = fn () => preg_match('/^\d+$/', $this->value()) !== 1;

        return $rule;
    }

    /** The identity number must be long enough. */
    private function minRule(): object
    {
        $rule = new stdClass();

        /** @var string Error message sent when the value fails the rule. */
        $rule->message = sprintf("The value '{$this->value}' is shorter than %d digits.", self::SIZE);

        /** @var callback Returns false, if the rule invalidates the value; otherwise, true. */
        $rule->fails = fn () => strlen($this->value()) < self::SIZE;

        return $rule;
    }

    /**
     * The identity number must be short enough.
     */
    private function maxRule(): object
    {
        $rule = new stdClass();

        /** @var string Error message sent when the value fails the rule. */
        $rule->message = sprintf("The value '{$this->value}' is longer than %d digits.", self::SIZE);

        /** @var callback Returns false, if the rule invalidates the value; otherwise, true. */
        $rule->fails = fn () => strlen($this->value()) > self::SIZE;

        return $rule;
    }

    /**
     * The identity number must start with the person's date of birth.
     */
    private function dateRule(): object
    {
        $rule = new stdClass();

        /** @var string Error message sent when the value fails the rule. */
        $rule->message = sprintf("The value '{$this->value}' does not start with a date in the format '%s'.", self::DATE_FORMAT);

        /** @var callback Returns false, if the rule invalidates the value; otherwise, true. */
        $rule->fails = function () {
            $date = DateTime::createFromFormat("!".self::DATE_FORMAT, $this->dateSegment());

            return ! $date || $date->format(self::DATE_FORMAT) !== $this->dateSegment();
        };

        return $rule;
    }

    /**
     * The identity number must indicate the citizenship classification of the
     * person.
     */
    private function citizenshipRule(): object
    {
        $rule = new stdClass();

        /** @var string Error message sent when the value fails the rule. */
        $rule->message = "The value '{$this->value}' does not have a valid citizenship classification.";

        /** @var callback Returns false if the rule invalidates the value; otherwise, true. */
        $rule->fails = fn () => ! in_array($this->citizenshipSegment(), [self::CITIZEN, self::PERMANENT_RESIDENT]);

        return $rule;
    }

    /**
     * The identity number must end with a digit that validates itself.
     */
    private function checkdigitRule(): object
    {
        $rule = new stdClass();

        /** @var  string Error message sent when the value fails the rule. */
        $rule->message = "The value '{$this->value}' has an invalid checksum digit.";

        /**
         * Implementation of the Luhn algorithm.
         *
         * @see {@link https://en.wikipedia.org/wiki/Luhn_algorithm}
         *
         * @var callback Returns false, if the rule invalidates the value; otherwise, true.
         */
        $rule->fails = function () {
            $digits = array_map('intval', str_split($this->value()));
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

            return $checksum % 10 !== 0;
        };

        return $rule;
    }
}
