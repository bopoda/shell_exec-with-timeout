<?php

declare(strict_types=1);

namespace ShellExec;

use Symfony\Component\Process\Process;

/**
 * exec example using symfony/process wrapper under proc_open func.
 * symfony/process supports timeout out of the box
 */
class ExecOption2SymfonyProcess
{
    public function exec(string $command, int $timeout = 5): Process
    {
        $process = Process::fromShellCommandline($command, timeout: $timeout);

        $process->mustRun();

        return $process;
    }
}