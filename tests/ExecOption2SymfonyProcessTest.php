<?php

declare(strict_types=1);

namespace ShellExec\Tests;

use PHPUnit\Framework\TestCase;
use ShellExec\ExecOption2SymfonyProcess;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class ExecOption2SymfonyProcessTest extends TestCase
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
        $this->expectException(ProcessTimedOutException::class);
        $this->expectExceptionMessageMatches('/The process.* exceeded the timeout of 2 seconds/i');

        $command = sprintf(
            'bash %sextremely-long-script.sh 2>&1',
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
        );

        $exec = $this->getTestClass();

        $exec->exec($command, timeout: 2);
    }

    private function getTestClass(): ExecOption2SymfonyProcess
    {
        return new ExecOption2SymfonyProcess();
    }
}