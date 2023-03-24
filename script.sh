#!/bin/bash

set -e;

echo "3rd-party shell output";

sleep 1;

echo "3rd-party shell output2";

#that's not necessary to write zero exit code. Anyway it will be zero at the very end of script
exit 0;