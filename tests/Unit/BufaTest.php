<?php

namespace Tests\Unit;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Common;
use Rechtlogisch\Steuernummer\Exceptions;

class BufaTest extends TestCase
{
    public function tearDown(): void
    {
        putenv('STEUERNUMMER_PRODUCTION=');
    }

    /** @dataProvider \Tests\Datasets\Bufas::bufasTestValidProvider() */
    function test_fails_for_test_bufa_with_federal_when_environment_is_production(string $federalState, string $bufa): void
    {
        putenv('STEUERNUMMER_PRODUCTION=true');
        $this->expectException(Exceptions\InvalidBufa::class);
        (new Common($bufa, $federalState))
            ->guardBufa();
    }

    /** @dataProvider \Tests\Datasets\Bufas::bufasTestValidProvider() */
    function test_does_not_throw_exception_for_test_bufa_with_federal_state_when_environment_is_not_production(string $federalState, string $bufa): void
    {
        putenv('STEUERNUMMER_PRODUCTION=false');
        (new Common($bufa, $federalState))
            ->guardBufa();

        $this->assertTrue(true);
    }

    function test_fails_for_test_bufa_without_federal_state_when_environment_is_production(): void
    {
        putenv('STEUERNUMMER_PRODUCTION=true');
        $this->expectException(Exceptions\InvalidBufa::class);
        (new Common('3098'))
            ->guardBufa();
    }

    function test_does_not_throw_exception_for_test_bufa_without_federal_state_when_environment_is_not_production(): void
    {
        putenv('STEUERNUMMER_PRODUCTION=false');
        (new Common('3098'))
            ->guardBufa();

        $this->assertTrue(true);
    }

    function test_fails_for_non_existing_test_bufa_without_federal_state_when_environment_is_not_production(): void
    {
        putenv('STEUERNUMMER_PRODUCTION=false');
        $this->expectException(Exceptions\InvalidBufa::class);
        (new Common('7777'))
            ->guardBufa();
    }
}
