<?php

namespace Tests\Feature;

/** @noinspection StaticClosureCanBeUsedInspection */

use Rechtlogisch\Steuernummer\Denormalize;
use Rechtlogisch\Steuernummer\Exceptions\InvalidBufa;
use PHPUnit\Framework\TestCase;

class DenormalizeTest extends TestCase
{
    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumberProvider() */
function test_denormalizesTaxNumberWithoutProvidedFederalState(string $federalState, string $steuernummer, string $elsterSteuernummer): void
{
    $denormalized = (new Denormalize($elsterSteuernummer))
        ->returnSteuernummerOnly();

    $this->assertSame($steuernummer, $denormalized);
}

    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumberProvider() */
function test_denormalizesTaxNumberWithProvidedFederalState(string $federalState, string $steuernummer, string $elsterSteuernummer): void
{
    $denormalized = (new Denormalize($elsterSteuernummer, $federalState))
        ->returnSteuernummerOnly();

    $this->assertSame($steuernummer, $denormalized);
}

    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumberProvider() */
function test_denormalizesTaxNumberAndReturnResultTogetherWithFederalState (string $federalState, string $steuernummer, string $elsterSteuernummer): void
{
    $result = (new Denormalize($elsterSteuernummer))
        ->returnWithFederalState();

    $this->assertIsArray($result);
    $this->assertSame($steuernummer, $result['steuernummer']);
    $this->assertSame($federalState, $result['federalState']);
}

    /** @dataProvider \Tests\Datasets\TaxNumbers::taxNumbersEdgeCasesBeValidProvider() */
function test_denormalizesEdgeCasesFromBE (string $federalState, string $steuernummer, string $elsterSteuernummer): void
{
    $denormalized = (new Denormalize($elsterSteuernummer, $federalState))
        ->returnSteuernummerOnly();

    $this->assertSame($steuernummer, $denormalized);
}

function test_returnsErrorsWhenSteuernummerWithNotWhitelistedBufaIsBeingTriedToBeDenormalized (): void
{
    $denormalized = (new Denormalize('1100081508150', 'BE'))
        ->run();

    $this->assertFalse($denormalized->isValid());
    $this->assertNotEmpty($denormalized->getErrors());
    $this->assertEquals(InvalidBufa::class, $denormalized->getFirstErrorKey());
    $this->assertNull($denormalized->getOutput());
}
}
