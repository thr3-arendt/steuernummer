<?php

namespace Tests\Unit;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Constants;

class ConstantsTest extends TestCase
{
    function test_list_validation_procedures_with_federal_state_names(): void
    {
        $result = Constants::listValidationProcedures();

        ksort($result);

        // print_r($result);

        $this->assertIsArray($result);
    }

    function test_groups_validation_procedures_by_method_name(): void
    {
        $result = Constants::groupValidationProcedures();

        // print_r($result);

        $this->assertIsArray($result);
    }

    function test_groups_factors_by_factor_digits(): void
    {
        $result = Constants::groupFactors();

        // print_r($result);

        $this->assertIsArray($result);
    }

    function test_lists_federal_states_names_with_iso_code_keys(): void
    {
        $result = Constants::federalStatesNames();

        // print_r($result);

        $this->assertIsArray($result);
    }

    function test_lists_federal_states_codes(): void
    {
        $result = Constants::federalStatesCodes();

        // print_r(implode(', ', $result));

        $this->assertIsArray($result);
    }

    function test_lists_federal_states_codes_with_steuernummer_10_digits(): void
    {
        $result = Constants::FEDERAL_STATES_STEUERNUMMER_10_DIGITS;

        // print_r(implode(', ', $result));

        $this->assertIsArray($result);
        $this->assertEquals(['BE', 'BW', 'HB', 'HH', 'NI', 'RP', 'SH'], $result);
    }

    function test_lists_federal_states_codes_with_steuernummer_11_digits(): void
    {
        $result = Constants::federalStatesSteuernummer11Digits();

        // print_r(implode(', ', $result));

        $values = array_values($result);
        $this->assertIsArray($values);
        $this->assertSame(['BB', 'BY', 'HE', 'MV', 'NW', 'SL', 'SN', 'ST', 'TH'], $values);
    }
}
