#!/bin/bash

#I call just sleep (sleep 100;) function in script to emulate long execution
#But let's imagine we call some 3rdparty binary which can be executed for extremely long time in some cases
#Let's image we can't fix that binary but we would like to set some timeout at higher level

set -e;

echo "3rd-party shell output";

sleep 100;

echo "3rd-party shell output2";

exit 0;