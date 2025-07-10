<?php

namespace Tests\Unit;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Common;
use Rechtlogisch\Steuernummer\Constants;
use Rechtlogisch\Steuernummer\Exceptions;

class CommonTest extends TestCase
{
    function stateProvider(): array
    {
        return [
            ['BB'],
            ['BE'],
            ['BW'],
            ['BY'],
            ['HB'],
            ['HE'],
            ['HH'],
            ['MV'],
            ['NI'],
            ['NW'],
            ['RP'],
            ['SH'],
            ['SL'],
            ['SN'],
            ['ST'],
            ['TH'],
        ];
    }

    /** @dataProvider stateProvider() */
    function test_passes_on_valid_federal_states(string $federalState): void
    {
        $common = new Common(null, $federalState);
        $common->guardFederalState();

        $this->assertTrue(true);
    }

    function test_fails_on_invalid_federal_states(): void
    {
        $this->expectException(Exceptions\InvalidFederalState::class);
        (new Common(null, 'XX'))
            ->guardFederalState();
    }

    function test_passes_on_elsterSteuernummer_with_correct_syntax(): void
    {
        $common = new Common('1234012345678', 'XX');
        $common->guardElsterSteuernummer();

        $this->assertTrue(true);
    }

    function test_fails_when_elsterSteuernummer_contains_non_digits () {
    $this->expectException(Exceptions\ElsterSteuernummerCanContainOnlyDigits::class);
    (new Common('X', 'XX'))
        ->guardElsterSteuernummer();
}

function test_fails_when_elsterSteuernummer_is_too_short(): void
{
    $this->expectException(Exceptions\InvalidElsterSteuernummerLength::class);
    (new Common('123401234567', 'XX'))
        ->guardElsterSteuernummer();
}

function test_fails_when_elsterSteuernummer_is_too_long(): void
{
    $this->expectException(Exceptions\InvalidElsterSteuernummerLength::class);
    (new Common('12340123456789', 'XX'))
        ->guardElsterSteuernummer();
}

function test_fails_when_the_formatKey_is_not_zero(): void
{
    $this->expectException(Exceptions\InvalidElsterSteuernummerFormatKey::class);
    (new Common('1234912345678', 'XX'))
        ->guardElsterSteuernummer();
}

/** @dataProvider \Tests\Datasets\FederalStates::federalStateProvider() */
function test_fails_when_an_invalid_district_is_being_provided(string $federalState): void
{
    $districtsNotAllowed = ['000', '998', '999'];

    foreach ($districtsNotAllowed as $district) {
        $suffix = '45678';
        if ($federalState === 'NW') {
            $district = str_pad($district, Constants::FEDERAL_STATES_DETAILS['NW']['districtLength'], '0', STR_PAD_LEFT);
            $suffix = '5678';
        }

        $elsterSteuernummer = '12340'.$district.$suffix;
        $this->expectException(Exceptions\InvalidDistrict::class);

        (new Common($elsterSteuernummer, $federalState))
            ->guardElsterSteuernummer();
    }
}

function test_fails_when_an_invalid_district_is_being_provided_in_NW(): void
{
    $districtsNotAllowed = ['0000', '0998', '0999'];

    foreach ($districtsNotAllowed as $district) {
        $elsterSteuernummer = '12340'.$district.'5678';

        $this->expectException(Exceptions\InvalidDistrict::class);
        (new Common($elsterSteuernummer, 'NW'))
            ->guardElsterSteuernummer();
    }
}

function districtLowerThan100Provider(): array
{
    return [
        ['BB'],
        ['BY'],
        ['MV'],
        ['SL'],
        ['SN'],
        ['ST'],
        ['TH'],
    ];
}

/** @dataProvider districtLowerThan100Provider() */
function test_fails_when_an_invalid_district_lower_than_100_is_being_provided_in_specific_federal_states(string $federalState): void
{
    $district = '099';
    $elsterSteuernummer = '12340'.$district.'45678';
    $this->expectException(Exceptions\InvalidDistrict::class);
    (new Common($elsterSteuernummer, $federalState))
        ->guardElsterSteuernummer();
}

function test_fails_when_unplausible_unique_numbers_and_checksum_is_provided_in_NW(): void
{
    $unplausible = '0001';
    $elsterSteuernummer = '123401234'.$unplausible;
    $this->expectException(Exceptions\InvalidElsterSteuernummer::class);
    (new Common($elsterSteuernummer, 'NW'))
        ->guardElsterSteuernummer();
}

/** @dataProvider \Tests\Datasets\Bufas::bufasInvalidProvider() */
function test_fails_when_a_not_supported_bufa_is_provided(string $federalState, string $bufa): void
{
    $this->expectException(Exceptions\InvalidBufa::class);
    (new Common($bufa, $federalState))
        ->guardBufa();
}

/** @dataProvider \Tests\Datasets\Bufas::bufasValidProvider() */
function test_passes_when_a_supported_bufa_is_provided(string $federalState, string $bufa): void
{
    (new Common($bufa, $federalState))
        ->guardBufa();

    $this->assertTrue(true);
}
}
