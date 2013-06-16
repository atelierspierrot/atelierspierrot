Dependencies
=============

This document explains the different tools we usually use to install/update dependencies in 
our packages.


Composer: PHP dependencies manager
----------------------------------

We use [Composer](http://getcomposer.org/) to automatically install/update the required 
external PHP packages our own packages are based on.

Some of our packages are registered in the [Packagist](http://packagist.org/packages/atelierspierrot/).


Sami: the new documentation generator
-------------------------------------

In the past we often used [PHP Documentor](http://www.phpdoc.org/) to generate a 
documentation of our scripts.

But we now use [Sami](http://github.com/fabpot/Sami) as it is really easy-to-use and is 
naturally managed by Composer.

As long as the package contains a `sami.config.php` file (*often at its root directory*), 
a documentation can be generated running:

    ~$ php path/to/vendor/sami/sami/sami.php render sami.config.php

The HTML documentation is generated in a `phpdoc/` directory that is NOT under version-control.
Sami requires temporary directories for this generation that are set, by default, to
the `__DIR__.'/../tmp/'` directory outside of the GIT clone:

    'build_dir' => __DIR__.'/phpdoc'
    'cache_dir' => __DIR__.'/../tmp/cache/NAMEOFPACKAGE'

The latest version of the `master` branch's documentation is available online for all of
our packages at <http://docs.ateliers-pierrot.fr>.


PHPUnit: the unit testing suite
-------------------------------

We try to prepare a set of unit-tests to validate each evolution of work on a package by
using [PHPUnit](http://github.com/sebastianbergmann/phpunit/). Our tests will often be
found in a `tests/` directory and the PHPUnit configuration resides in the file :
`phpunit.xml.dist`. If you find this file in a package, you can run:

    ~$ path/to/phpunit

Use option `-h` for the help ; many configuration settings can be defined as command
line options.

Most of our packages that uses some unit-tests are integrated in the 
[Travis CI](http://travis-ci.org/) automatic website, that validate the package after each
commit.


----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
