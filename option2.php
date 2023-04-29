<?php

declare(strict_types=1);

use Symfony\Component\Process\Process;

require_once 'vendor/autoload.php';

$command = sprintf('bash %s/extremely-long-script.sh 2>&1', __DIR__);

$process = Process::fromShellCommandline($command, timeout: 5);

$process->mustRun();

echo 'Output: ' . $process->getOutput();