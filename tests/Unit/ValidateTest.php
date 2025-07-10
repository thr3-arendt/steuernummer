<?php

namespace Tests\Unit;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\Constraint\TraversableContainsEqual;
use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Dto\ValidationResult;
use Rechtlogisch\Steuernummer\Validate;

class ValidateTest extends TestCase
{
    function test_returns_a_ValidationResult_on_valid_input(): void
    {
        $result = new Validate('1121081508150', 'BE');
        $this->assertIsObject($result);
        // TODO: check if Validate contains ValidationResult
    }

    function test_returns_a_ValidationResult_on_invalid_input(): void
    {
        $result = new Validate('1', 'X');
        $this->assertIsObject($result);
        // TODO: check if Validate contains ValidationResult
    }

    function test_fails_when_no_elsterSteuernummer_was_provided(): void
    {
        $this->expectException(\TypeError::class);
        /** @phpstan-ignore-next-line */
        new Validate(null, 'BE');
    }

    function test_fails_when_null_was_provided_as_elsterSteuernummer(): void
    {
        $this->expectException(\TypeError::class);
        /** @phpstan-ignore-next-line */
        new Validate(null, 'BE');
    }

    function test_does_not_throw_an_exception_when_no_federalState_was_provided(): void
    {
        new Validate('1121081508150');
        $this->assertTrue(true);
    }

    function test_returns_false_when_the_federal_state_is_too_short_and_federal_state_not_provided(): void
    {
        $result = (new Validate('112105678901'))->run();
        $this->assertFalse($result->isValid());
    }

    function test_returns_false_for_an_invalid_BUFA_provided_in_BE(): void
    {
        $result = (new Validate('1234012345678', 'BE'))->run();
        $this->assertFalse($result->isValid());
    }

    function test_returns_array_as_first_error_for_an_invalid_BUFA_provided_in_BE(): void
    {
        $result = (new Validate('1234012345678', 'BE'))->run();
        $firstError = $result->getFirstError();
        $this->assertIsArray($firstError);
        $this->assertNotEmpty($firstError);
    }

    /** @dataProvider \Tests\Datasets\EdgeCases::taxNumbersEdgeCasesZweierProcedureProvider() */
    function test_validates_correctly_a_tax_number_with_checksum_0_for_zweierProcedure(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Validate($elsterSteuernummer, $federalState))
            ->run();

        $this->assertTrue($result->isValid());
    }
}
