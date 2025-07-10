<?php

namespace Tests\Unit;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Dto\NormalizationResult;
use Rechtlogisch\Steuernummer\Exceptions;
use Rechtlogisch\Steuernummer\Normalize;

class NormalizeTest extends TestCase
{
    function test_checks_the_happy_path(): void
    {
        $input = '2181508150';
        $result = (new Normalize($input, 'BE'))
            ->run();

        $this->assertInstanceOf(NormalizationResult::class, $result);
        $this->assertTrue($result->isValid());
        $this->assertSame($input, $result->getInput());
        $this->assertSame('1121081508150', $result->getOutput());
        $this->assertEmpty($result->getErrors());
    }

    /** @dataProvider \Tests\Datasets\FederalStates::federalStateProvider() */
    function test_fails_when_steuernummer_is_too_short(string $federalState): void
    {
        $result = (new Normalize('123456789', $federalState))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertSame(Exceptions\InvalidSteuernummerLength::class, $result->getFirstErrorKey());
    }

    /** @dataProvider \Tests\Datasets\FederalStates::federalStateProvider() */
    function test_fails_when_steuernummer_is_too_long(string $federalState): void
    {
        $result = (new Normalize('123456789012', $federalState))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertSame(Exceptions\InvalidSteuernummerLength::class, $result->getFirstErrorKey());
    }

    /** @dataProvider \Tests\Datasets\FederalStates::federalStatesSteuerNummer10DigitsProvider() */
    function test_fails_when_steuernummer_is_too_long_in_federal_states_where_a_10_digit_long_steuernummer_is_being_expected(string $federalState): void
    {
        $result = (new Normalize('12345678901', $federalState))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertSame(Exceptions\InvalidSteuernummerLength::class, $result->getFirstErrorKey());
    }

    /** @dataProvider \Tests\Datasets\FederalStates::federalStatesSteuerNummer11DigitsProvider() */
    function test_fails_when_steuernummer_is_too_short_in_federal_states_where_a_11_digit_long_steuernummer_is_being_expected(string $federalState): void
    {
        $result = (new Normalize('1234567890', $federalState))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertSame(Exceptions\InvalidSteuernummerLength::class, $result->getFirstErrorKey());
    }

    function test_runs_with_int_values_as_steuernummer(): void
    {
        // PHP casts int to string due to the type hint in class constructor
        // https://www.php.net/manual/en/language.types.string.php#language.types.string.casting
        $input = 2181508150;
        /** @phpstan-ignore-next-line */
        $result = (new Normalize($input, 'BE'))
            ->run();

        $this->assertTrue($result->isValid());
        $this->assertSame((string)$input, $result->getInput());
        $this->assertSame('1121081508150', $result->getOutput());
        $this->assertEmpty($result->getErrors());
    }

    function test_fails_when_steuernummer_is_not_stringable(): void
    {
        $input = new \stdClass;
        $this->expectException(\TypeError::class);
        /** @noinspection PhpParamsInspection @phpstan-ignore-next-line */
        new Normalize($input, 'XX');
    }

    function test_fails_when_steuernummer_is_null(): void
    {
        $this->expectException(\TypeError::class);
        /** @phpstan-ignore-next-line */
        new Normalize(null, 'XX');
    }

    function test_fails_when_federalState_is_null(): void
    {
        $this->expectException(\TypeError::class);
        /** @phpstan-ignore-next-line */
        new Normalize('1121081508150', null);
    }

    function test_fails_on_invalid_federal_states(): void
    {
        $this->expectException(Exceptions\InvalidFederalState::class);
        (new Normalize('1121081508150', 'XX'))
            ->guardFederalState();
    }

    function test_fails_when_federalState_is_not_stringable(): void
    {
        $input = new \stdClass;
        $this->expectException(\TypeError::class);
        /** @noinspection PhpParamsInspection @phpstan-ignore-next-line */
        new Normalize('1121081508150', $input);
    }
}
