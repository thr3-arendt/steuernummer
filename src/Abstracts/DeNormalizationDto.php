<?php

declare(strict_types=1);

namespace Rechtlogisch\Steuernummer\Abstracts;

abstract class DeNormalizationDto extends ResultDto
{
    /** @var string|null */
    private $input = null;

    /** @var string|null */
    private $output = null;

    public function setInput(?string $input): void
    {
        $this->input = $input;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function setOutput(?string $output): void
    {
        $this->output = $output;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }
}
