# Shell exec with timeout (PHP)

The main purpose of this small repository is demonstration
of calling exec any command from PHP code (like shell_exec) with timeout 
for its execution.

Who knows how long this one could be executed?
```php
shell_exec('./script.bin');
$a = 'df';
```
It depends ONLY on the binary file itself. PHP itself will wait till the script entirely finished.
You can read more about shell exec with timeout article in russian.
