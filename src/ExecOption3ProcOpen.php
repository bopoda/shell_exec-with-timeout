<?php

declare(strict_types=1);

namespace ShellExec;

use Exception;

/**
 * Let's write minimalistic class something like symfony/process but using only necessary amount of code.
 * Our implementation should be able to interrupt long command execution according to timeout
 * Will work on *nix, not tested on Windows.
 *
 * Execute a command and return its exit code and output.
 * Either wait until the command exits itself or the timeout is reached.
 *
 * @throws Exception
 */
class ExecOption3ProcOpen
{
    public function exec(string $command, int $timeout = 5): ExecResult
    {
        // File descriptors passed to the process.
        $descriptors = [
            ['pipe', 'r'],  // stdin
            ['pipe', 'w'],  // stdout
            ['pipe', 'w'],  // stderr
        ];

        $startTime = microtime(true);

        // Start the process.
        $process = proc_open($command, $descriptors, $pipes);

        if (!is_resource($process)) {
            throw new Exception('Could not execute process');
        }

        // Set the pipes to non-blocking
        foreach ($pipes as $pipe) {
            stream_set_blocking($pipe, false);
        }

        // Turn the timeout into milliseconds.
        $timeoutMillis = $timeout * 1000;

        // Output buffer.
        $buffer = '';

        do {
            // collect stdout and stderr both, but you can do it separately if you want
            $buffer .= stream_get_contents($pipes[1]).stream_get_contents($pipes[2]);

            //sleep for some time (1ms) and verify the time limit and the process status
            usleep(1000);

            $status = proc_get_status($process);
            $exitCode = $status['exitcode']; // exitcode = -1 when still running
        } while (!$this->exceededTimeout($startTime, $timeoutMillis) && $status['running']);

        // read the remaining data from pipes
        $buffer .= stream_get_contents($pipes[1]).stream_get_contents($pipes[2]);

        // Close all streams
        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        // Close the process
        proc_close($process);

        return new ExecResult(
            $exitCode,
            $buffer
        );
    }

    private function exceededTimeout(float $startTime, int $timeoutMillis): bool
    {
        $exceededTimeLimit = $startTime * 1000 + $timeoutMillis < microtime(true) * 1000;

        if ($exceededTimeLimit) {
            // you can use customException as well instead of generic
            throw new Exception(sprintf(
                'The process exceeded the timeout of %d seconds.',
                $timeoutMillis / 1000,
            ));
        }

        return false;
    }
}