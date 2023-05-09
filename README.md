# Shell exec with timeout (PHP)

The main purpose of this small repository is demonstration
of calling exec any command from PHP code (like shell_exec) with timeout 
for its execution.

Who knows how long this one `shell_exec` can take?

```php
shell_exec('./script.bin');
```

It depends ONLY on the binary file itself.

PHP itself will wait till the script entirely finished.

You can read more about [shell exec with timeout in article](https://jeka.by/post/1113/PHP-shell_exec-with-timeout/)
written in russian.
