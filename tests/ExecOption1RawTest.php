<?php

declare(strict_types=1);

namespace ShellExec\Tests;

use PHPUnit\Framework\TestCase;
use ShellExec\ExecOption1Raw;

class ExecOption1RawTest extends TestCase
{
    public function testExecute(): void
    {
        $exec = $this->getTestClass();

        $execResult = $exec->exec('echo "exec start output"; sleep 1; echo "end output"');

        self::assertSame(0, $execResult->getExitCode(), 'successful command exit status');
        self::assertSame(<<<ExpextedExecOutput
exec start output
end output
ExpextedExecOutput, $execResult->getOutput(), 'command output');
    }

    public function testExecuteLongScript(): void
    {
        $this->markTestSkipped('Skip as this test reproduces very long command execution.
         I would expect timeout Exception thrown.
         This testcase shows main disadvantage of raw exec method.  
         ');

        $command = sprintf(
            'bash %sextremely-long-script.sh 2>&1',
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
        );

        $exec = $this->getTestClass();

        $execResult = $exec->exec($command);

        self::assertSame(
            0,
            $execResult->getExitCode(),
            'Would you expect successful command exit status is expected or timeout exception?!'
        );
    }

    private function getTestClass(): ExecOption1Raw
    {
        return new ExecOption1Raw();
    }
}