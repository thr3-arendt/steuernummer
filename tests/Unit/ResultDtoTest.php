<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Rechtlogisch\Steuernummer\Dto\ValidationResult;

class ResultDtoTest extends TestCase
{
    function test_returns_null_as_first_error_when_no_errors_set(): void
    {
        $dto = new ValidationResult;
        $firstError = $dto->getFirstError();

        $this->assertNull($firstError);
    }
}
