<?php

namespace Tests\Feature;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Exceptions\InvalidBufa;
use Rechtlogisch\Steuernummer\Normalize;

class NormalizeTest extends TestCase
{
    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumberProvider() */
    function test_normalizes_tax_number(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Normalize($steuernummer, $federalState))
            ->run();

        $this->assertTrue($result->isValid());
        $this->assertSame($elsterSteuernummer, $result->getOutput());
    }

    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumberProvider() */
    function test_normalizes_tax_number_returning_only_elster_steuernummer(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Normalize($steuernummer, $federalState))
            ->returnElsterSteuernummerOnly();

        $this->assertSame($elsterSteuernummer, $result);
    }

    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumbersEdgeCasesBeValidProvider() */
    function test_normalizes_edge_cases_from_BE(string $federalState, string $steuernummer, string $elsterSteuernummer): void
    {
        $result = (new Normalize($steuernummer, $federalState))
            ->run();

        $this->assertTrue($result->isValid());
        $this->assertSame($elsterSteuernummer, $result->getOutput());
    }

    function test_returns_errors_when_steuernummer_with_not_whitelisted_bufa_is_being_tried_to_be_normalized(): void
    {
        $result = (new Normalize('00/815/08150', 'BE'))
            ->run();

        $this->assertFalse($result->isValid());
        $this->assertNotEmpty($result->getErrors());
        $this->assertSame(InvalidBufa::class, $result->getFirstErrorKey());
        $this->assertNull($result->getOutput());
    }
}
