# pdepend-process

`pdepend-process` processes data from [PHP_Depend](http://pdepend.org/).

## Installation

### PHP Archive (PHAR)

The easiest way to obtain `pdepend-process` is to download a [PHP Archive (PHAR)](http://php.net/phar) that has all required dependencies of `pdepend-process` bundled in a single file:

    $ wget https://phar.phpunit.de/pdepend-process.phar
    $ chmod +x pdepend-process.phar
    $ mv pdepend-process.phar /usr/local/bin/pdepend-process

You can also immediately use the PHAR after you have downloaded it, of course:

    $ wget https://phar.phpunit.de/pdepend-process.phar
    $ php pdepend-process.phar

### Composer

You can add this tool as a local, per-project, development-time dependency to your project using [Composer](https://getcomposer.org/):

    $ composer require --dev sebastian/pdepend-process

You can then invoke it using the `vendor/bin/pdepend-process` executable.
