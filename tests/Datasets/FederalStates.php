<?php

namespace Tests\Datasets;

use Rechtlogisch\Steuernummer\Constants;

class FederalStates
{
    public function federalStateProvider(): array
    {
        return array_map(function ($code) {
            return [$code];
        }, Constants::federalStatesCodes());
    }

    public function federalStatesSteuerNummer10DigitsProvider(): array
    {
        return array_map(function ($code) {
            return [$code];
        }, Constants::FEDERAL_STATES_STEUERNUMMER_10_DIGITS);
    }

    public function federalStatesSteuerNummer11DigitsProvider(): array
    {
        return array_map(function ($code) {
            return [$code];
        }, Constants::federalStatesSteuernummer11Digits());
    }
}
