<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Exceptions\InvalidElsterSteuernummerCheckDigit;
use Rechtlogisch\Steuernummer\Exceptions\InvalidSteuernummerLength;

class FunctionsTest extends TestCase
{
    function test_normalizes_a_steuernummer_with_the_global_normalizeSteuernummer(): void
    {
        $result = normalizeSteuernummer('21/815/08150', 'BE');

        $this->assertSame('1121081508150', $result);
    }

    function test_return_false_when_invalid_steuernummer_with_the_global_normalizeSteuernummer(): void
    {
        $result = normalizeSteuernummer('00/815/08150', 'BE');

        $this->assertNull($result);
    }

    function test_denormalizes_an_elster_steuernummer_with_the_global_denormalizeSteuernummer(): void
    {
        $result = denormalizeSteuernummer('1121081508150');

        $this->assertSame('21/815/08150', $result);
    }

    function test_denormalizes_an_elster_steuernummer_with_the_global_denormalizeSteuernummer_function_and_returns_details(): void
    {
        /** @var array{steuernummer: string, federalState: string} $result */
        $result = denormalizeSteuernummer('1121081508150', null, true);
        $this->assertIsArray($result);
        $this->assertSame('21/815/08150', $result['steuernummer']);
        $this->assertSame('BE', $result['federalState']);
    }

    function test_validates_an_elster_steuernummer_with_the_global_validateElsterSteuernummer(): void
    {
        $result = validateElsterSteuernummer('1121081508150');
        $this->assertTrue($result->isValid());
    }

    function test_checks_if_a_elster_steuernummer_is_valid_with_global_isElsterSteuernummerValid_and_without_a_federal_state_function(): void
    {
        $result = isElsterSteuernummerValid('1121081508150');
        $this->assertTrue($result);
    }

    function test_checks_if_a_elster_steuernummer_is_valid_with_global_isElsterSteuernummerValid_and_with_a_federal_state_function(): void
    {
        $result = isElsterSteuernummerValid('1121081508150', 'BE');
        $this->assertTrue($result);
    }

    function test_validates_a_steuernummer_with_the_global_validateSteuernummer(): void
    {
        $result = validateSteuernummer('21/815/08150', 'BE');
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    function test_returns_errors_for_invalid_steuernummer_with_the_global_validateSteuernummer(): void
    {
        $result = validateSteuernummer('21/815/08151', 'BE');

        $this->assertFalse($result->isValid());
        $this->assertNotEmpty($result->getErrors());
        $this->assertSame(InvalidElsterSteuernummerCheckDigit::class, $result->getFirstErrorKey());
    }

    function test_returns_errors_for_irrational_steuernummer_with_the_global_validateSteuernummer(): void
    {
        $result = validateSteuernummer('1', 'BE');

        $this->assertFalse($result->isValid());
        $this->assertNotEmpty($result->getErrors());
        $this->assertSame(InvalidSteuernummerLength::class, $result->getFirstErrorKey());
    }

    function test_checks_if_a_steuernummer_is_valid_with_global_isSteuernummerValid(): void
    {
        $result = isSteuernummerValid('21/815/08150', 'BE');
        $this->assertTrue($result);
    }

    function test_returns_false_when_steuernummer_is_invalid_with_global_isSteuernummerValid(): void
    {
        $result = isSteuernummerValid('21/815/08151', 'BE');
        $this->assertFalse($result);
    }

    function test_returns_false_when_provided_steuernummer_is_irrational_with_global_isSteuernummerValid(): void
    {
        $result = isSteuernummerValid('1', 'BE');
        $this->assertFalse($result);
    }
}
