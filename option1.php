<?php

declare(strict_types=1);

$command = sprintf('bash %s/extremely-long-script.sh 2>&1', __DIR__);

//this call can cost a fortune depending on $command and what happens inside the command' execution
//Long execution can cause problems in case of php-fpm (for example 504 Bad Gateway response)
exec($command, $output, $code);

echo 'Exit Code: ' . $code . PHP_EOL;
echo 'Output: ' . implode(PHP_EOL, $output);
