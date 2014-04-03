# PHP_Depend Data Processor

`pdepend-process` processes data from [PHP_Depend](http://pdepend.org/).

## Installation

### PHP Archive (PHAR)

The easiest way to obtain pdepend-process is to download a [PHP Archive (PHAR)](http://php.net/phar) that has all required dependencies of pdepend-process bundled in a single file:

    wget https://phar.phpunit.de/pdepend-process.phar
    chmod +x pdepend-process.phar
    mv pdepend-process.phar /usr/local/bin/pdepend-process

You can also immediately use the PHAR after you have downloaded it, of course:

    wget https://phar.phpunit.de/pdepend-process.phar
    php pdepend-process.phar

### Composer

Simply add a dependency on `sebastian/pdepend-process` to your project's `composer.json` file if you use [Composer](http://getcomposer.org/) to manage the dependencies of your project. Here is a minimal example of a `composer.json` file that just defines a development-time dependency on pdepend-process:

    {
        "require-dev": {
            "sebastian/pdepend-process": "*"
        }
    }

For a system-wide installation via Composer, you can run:

    composer global require 'sebastian/pdepend-process=*'

Make sure you have `~/.composer/vendor/bin/` in your path.

## Usage Example

    pdepend --summary-xml=summary.xml /path/to/source

    pdepend-process --dashboard-html /path/to/target summary.xml
