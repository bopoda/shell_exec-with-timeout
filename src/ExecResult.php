<?php

declare(strict_types=1);

namespace ShellExec;

class ExecResult
{
    public function __construct(
        private readonly int $exitCode,
        private readonly string $output,
    ) {
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}