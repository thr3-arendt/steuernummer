<?php

namespace Tests\Datasets;

// test examples are valid, but might be not plausible

class EdgeCases
{
    public function taxNumbersEdgeCasesZweierProcedureProvider(): array
    {
        return [
            ['BW', '6513/58340', '2865013528340'],
        ];
    }

    public function taxNumbersEdgeCasesBeInvalid(): array
    {
        return [
            ['BE', '18/101/08150', '1118010108150'],
        ];
    }
}

// dataset('tax-numbers-edge-cases-nw', [
    // ['NW', '400/9981/2342', '5400099812342'],
    // ['NW', '400/9991/2347', '5400099912347'],
// ]);
