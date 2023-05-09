<?php

declare(strict_types=1);

namespace ShellExec;

/**
 * Raw exec example
 * It has one disadvantage - you can not control execution time of process.
 * But if you are pretty sure in script which you called than you can use raw exec.
 */
class ExecOption1Raw
{
    public function exec(string $command): ExecResult
    {
        //This call can cost a fortune depending on $command and what happens inside the command' execution
        //Long execution can cause problems in case of php-fpm (for example 504 Bad Gateway response)
        //But depending on logic, we might no need waiting for a really long time if we want
        //to interrupt the process after some timeout.
        //It's IMPOSSIBLE to apply timeout to exec or shell_exec functions.
        exec($command, $output, $code);

        return new ExecResult(
            $code,
            implode(PHP_EOL, $output)
        );
    }
}