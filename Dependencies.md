Dependencies
=============

This document explain the different tools we usually use to install/update dependencies in 
our packages.


## Composer: PHP dependencies manager

We use [Composer](http://getcomposer.org/) to automatically install/update the required 
external PHP packages our own packages are based on.


## Sami: the new documentation generator

In the past we often used [PHP Documentor](http://www.phpdoc.org/) to generate a 
documentation of our scripts.

But we now use [Sami](https://github.com/fabpot/Sami) as it is really easy-to-use and is 
naturally managed by Composer.

As long as the package contains a `sami.config.php` file (*often at its root directory*), 
a documentation can be generated running:

    ~$ php path/to/vendor/sami/sami/sami.php render sami.config.php

The HTML documentation is generated in a `phpdoc/` directory that is NOT under version-control.
Sami requires two temporary directories for this generation that are set, by default, to
the `__DIR__.'/../tmp/'` directory outside of the GIT clone:

    'build_dir' => __DIR__.'/../tmp/build/NAMEOFPACKAGE'
    'cache_dir' => __DIR__.'/../tmp/cache/NAMEOFPACKAGE'


----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
