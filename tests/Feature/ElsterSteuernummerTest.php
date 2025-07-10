<?php

namespace Tests\Feature;

/** @noinspection StaticClosureCanBeUsedInspection */

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Exceptions;
use Rechtlogisch\Steuernummer\Validate;

class ElsterSteuernummerTest extends TestCase
{
    function test_generates_a_csv_for_comparison_with_eric_result(): void
    {
        $path = 'tests/Datasets/';
        $filename = 'input-dummy.txt';
        $lines = file($path.$filename);
        if ($lines === false) {
            exit("Error reading {$filename}".PHP_EOL);
        }
        $result = [];

        foreach ($lines as $line) {
            $elsterSteuernummer = trim($line);
            if ($elsterSteuernummer === '') {
                continue;
            }

            $resultValidation = (new Validate($elsterSteuernummer))
                ->run();

            $codeMap = [
                Exceptions\InvalidElsterSteuernummerCheckDigit::class => '610001034',
                Exceptions\FederalStateCouldNotBeDetermined::class => '610001035',
                Exceptions\InvalidElsterSteuernummerLength::class => '610001035',
                Exceptions\InvalidBufa::class => '610001038',
            ];

            $code = ($resultValidation->isValid() === true)
                ? '0'
                : $codeMap[$resultValidation->getFirstErrorKey()];

            $textResultValidation = ($resultValidation->isValid() === true)
                ? 'valid'
                : 'invalid';

            $text = "{$elsterSteuernummer},{$textResultValidation},{$code}".PHP_EOL;

            $result[] = $text;
        }

        $resultSaving = file_put_contents($path.'result-dummy.csv', implode('', $result));

        $this->assertIsInt($resultSaving);
    }
}
