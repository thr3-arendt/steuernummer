<?php

namespace Tests\Feature;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Validate;

class ValidateTest extends TestCase
{
    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumberProvider() */
    function test_validates_tax_number(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Validate($elsterSteuernummer))
            ->run();

        $this->assertTrue($result->isValid());
    }


    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumberProvider() */
    function test_validates_tax_number_with_provided_federalState(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Validate($elsterSteuernummer, $federalState))
            ->run();

        $this->assertTrue($result->isValid());
    }

    function test_returns_false_and_an_error_when_incorrect_federalState_provided(): void
    {
        $elsterSteuernummer = '1121081508150'; // from BE
        $result = (new Validate($elsterSteuernummer, 'NW'))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertContains('BUFA 1121 is not supported in federalState NW', $result->getFirstError());
    }

    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumbersEdgeCasesBeValidProvider() */
    function test_validates_edge_cases_from_BE(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Validate($elsterSteuernummer, $federalState))
            ->run();

        $this->assertTrue($result->isValid());
    }

    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumbersInvalidProvider() */
    function test_return_false_for_invalid_elsterSteuernummer(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Validate($elsterSteuernummer, $federalState))
            ->run();

        $this->assertFalse($result->isValid());
    }

    /** @dataProvider \Tests\Datasets\EdgeCases::taxNumbersEdgeCasesBeInvalid() */
    function test_return_false_for_elsterSteuernummer_edge_cases_from_BE(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Validate($elsterSteuernummer, $federalState))
            ->run();

        $this->assertFalse($result->isValid());
    }
}
