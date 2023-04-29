<?php

/**
 * PHP shell_exec with timeout handling
 * You can use code below if you don't want symfony/process package as dependency to project
 */

declare(strict_types=1);

$command = sprintf('bash %s/extremely-long-script.sh 2>&1', __DIR__);

$result = execWithTimeout($command, 4);

echo 'Exit code: '.$result[0].PHP_EOL;
echo 'Output from script: '.$result[1].PHP_EOL;

/**
 *
 * Let's write something like symfony/process but using minimal amount of code.
 * Will work on *nix, not verified on Windows.
 *
 * Execute a command and return it's exit code and output.
 * Either wait until the command exits or the timeout has expired.
 *
 * @throws Exception
 */
function execWithTimeout(string $cmd, int $timeout): array
{
    // File descriptors passed to the process.
    $descriptors = [
        ['pipe', 'r'],  // stdin
        ['pipe', 'w'],  // stdout
        ['pipe', 'w'],  // stderr
    ];

    $startTime = microtime(true);

    // Start the process.
    $process = proc_open($cmd, $descriptors, $pipes);

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
    } while (!exceededTimeout($startTime, $timeoutMillis) && $status['running']);

    // read the remaining data from pipes
    $buffer .= stream_get_contents($pipes[1]).stream_get_contents($pipes[2]);

    // Close all streams
    foreach ($pipes as $pipe) {
        fclose($pipe);
    }

    // Close the process
    proc_close($process);

    // Return array for test purposes. You can handle exit code right here and throw exception if exitcode !=0
    // Also you can create object (DTO) to have access to all parameters of result
    return [$exitCode, $buffer];
}

function exceededTimeout(float $startTime, int $timeoutMillis): bool
{
    $exceededTimeLimit = $startTime * 1000 + $timeoutMillis < microtime(true) * 1000;

    if ($exceededTimeLimit) {
        throw new Exception(sprintf(
            'The process exceeded the timeout of %d seconds.',
            $timeoutMillis / 1000,
        ));
    }

    return false;
}