<?php

namespace Tests\Unit;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Denormalize;
use Rechtlogisch\Steuernummer\Dto\DenormalizationResult;
use Rechtlogisch\Steuernummer\Exceptions;

class DenormalizeTest extends TestCase
{
    function test_checks_the_happy_path(): void
    {
        $input = '1121081508150';
        $result = (new Denormalize($input, 'BE'))
            ->run();

        $this->assertInstanceOf(DenormalizationResult::class, $result);
        $this->assertNotNull($result);
        $this->assertTrue($result->isValid());
        $this->assertSame($input, $result->getInput());
        $this->assertSame('21/815/08150', $result->getOutput());
        $this->assertSame('BE', $result->getFederalState());
        $this->assertEmpty($result->getErrors());
    }

    /** @dataProvider \Tests\Datasets\FederalStates::federalStateProvider */
    function test_fails_when_elsterSteuernummer_is_too_short(string $federalState): void
    {
        $result = (new Denormalize('123456789012', $federalState))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertSame(Exceptions\InvalidElsterSteuernummerLength::class, $result->getFirstErrorKey());
    }

    function test_fails_when_the_federal_state_is_too_short_and_federal_state_not_provided(): void
    {
        $result = (new Denormalize('1'))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertSame(Exceptions\InvalidElsterSteuernummerLength::class, $result->getFirstErrorKey());
    }

    /** @dataProvider \Tests\Datasets\FederalStates::federalStateProvider */
    function test_fails_when_steuernummer_is_too_long(string $federalState): void
    {
        $result = (new Denormalize('12345678901234', $federalState))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertSame(Exceptions\InvalidElsterSteuernummerLength::class, $result->getFirstErrorKey());
    }

    function test_returns_only_denormalized_string(): void
    {
        $denormalized = (new Denormalize('1121081508150', 'BE'))
            ->returnSteuernummerOnly();

        $this->assertIsString($denormalized);
    }

    function test_returns_only_denormalized_string_when_valid_elsterSteuernummer_as_int_provided(): void
    {
        // PHP casts int to string due to the type hint in class constructor
        // https://www.php.net/manual/en/language.types.string.php#language.types.string.casting
        /** @phpstan-ignore-next-line */
        $denormalized = (new Denormalize(1121081508150, 'BE'))
            ->returnSteuernummerOnly();

        $this->assertIsString($denormalized);
    }

    function test_fails_when_elsterSteuernummer_is_not_stringable(): void
    {
        $input = new \stdClass;
        $this->expectException(\TypeError::class);
        /** @noinspection PhpParamsInspection @phpstan-ignore-next-line */
        new Denormalize($input, 'XX');
    }

    function test_fails_when_elsterSteuernummer_is_null(): void
    {
        $this->expectException(\TypeError::class);
        /** @phpstan-ignore-next-line */
        new Denormalize(null, 'XX');
    }

    function test_fails_on_invalid_federal_states(): void
    {
        $this->expectException(Exceptions\InvalidFederalState::class);
        (new Denormalize('1121081508150', 'XX'))
            ->guardFederalState();
    }

    function test_fails_when_federalState_is_not_stringable(): void
    {
        $input = new \stdClass;
        $this->expectException(\TypeError::class);
        /** @noinspection PhpParamsInspection @phpstan-ignore-next-line */
        new Denormalize('1121081508150', $input);
    }
}
