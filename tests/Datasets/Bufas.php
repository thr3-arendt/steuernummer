<?php

namespace Tests\Datasets;

class Bufas
{
    public function bufasValidProvider(): array
    {
        return [
            ['HH', '2241'],
            ['NW', '5205'],
        ];
    }

    public function bufasTestValidProvider(): array
    {
        return [
            ['BB', '3098'],
            ['BE', '1197'],
            ['BW', '2866'],
            ['BY', '9198'],
            ['BY', '9296'],
            ['HB', '2497'],
            ['HE', '2653'],
            // HH has no test BUFAs
            ['MV', '4098'],
            ['NI', '2388'],
            ['NW', '5400'],
            ['NW', '5500'],
            ['NW', '5600'],
            ['RP', '2799'],
            ['SH', '2138'],
            ['SL', '1096'],
            ['SN', '3248'],
            ['ST', '3198'],
            ['TH', '4198'],
        ];
    }

    public function bufasInvalidProvider(): array
    {
        return [
            ['XX', '1234'],
            ['BE', '1100'],
            ['NW', '5999'],
            ['NW', '5380'], // not yet supported
        ];
    }
}
