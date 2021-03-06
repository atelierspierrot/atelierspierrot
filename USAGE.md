Usage of our PHP packages
=========================

[![demonstrations](http://img.ateliers-pierrot-static.fr/see-the-demo.svg)](http://sites.ateliers-pierrot.fr/)
[![documentations](http://img.ateliers-pierrot-static.fr/read-the-doc.svg)](http://docs.ateliers-pierrot.fr/)
[![manuals](http://img.ateliers-pierrot-static.fr/read-the-man.svg)](http://mans.ateliers-pierrot.fr/)

This document explains how to install and use our packages in your work.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", 
"RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described 
in [RFC 2119](http://www.ietf.org/rfc/rfc2119.txt).


First notes about standards
---------------------------

We always try to follow the coding standards and naming rules most commonly in use:

-   the [PEAR coding standards](http://pear.php.net/manual/en/standards.php)
-   the [PHP Framework Interoperability Group standards](https://github.com/php-fig/fig-standards).

Knowing that, all our classes are named and organized in an architecture to allow the use of the
[standard SplClassLoader](https://gist.github.com/jwage/221634). We have made an improved version
of this loader to be able to use the internal `class_exists()` function with its natural behavior.
You can find this fork at <http://gist.github.com/piwi/0e7f1560365162134725>.


Architecture
------------

Our packages are (mostly) constructed in a matter of *granularity*: each one is a piece of
a global construction which is the result of the aggregation of many packages. But each package
MUST be usable "as is" as long as its own dependencies are embedded.

We follow a common package architecture to keep compliant with PHP packages' best practices:

-   the PHP sources are stored in a `src/PackageNamespace/` directory,
-   any binary is stored in a `bin/` directory,
-   the tests classes are stored in a `tests/` directory,
-   the documentation is stored in a `phpdoc/` directory.

A classic package SHOULD be constructed like:

    PACKAGE_ROOT/
    |------------- bin/                     // binaries
    |------------- demo/                    // a demonstation - optional
    |------------- phpdoc/                  // the documentation - optional
    |------------- src/PackageNamespace/    // PHP sources
    |------------- tests/                   // test classes

To construct such packages, we ALWAYS use [Composer](http://getcomposer.org/) as a distributor
and dependencies installer and auto-loader. Each package MAY contain a `composer.json`
manifest file at its root directory to define its name and dependencies constraints. If such
file is present, reading it MUST be sufficient to let you know what it does and on which other 
packages it depends.

We mostly let *Composer* install dependencies and binaries following its default architecture:

-   sources MAY be installed in a `vendor/VENDOR/PACKAGE/` directory
-   binaries MAY be installed in the `vendor/bin/` directory.


Installation
------------

You can *use* a package in your work in many ways, but the *best practice* is ALWAYS to
install it via *Composer* directly.

### Using a hard copy

#### Clone a repository

First, you can clone the [GitHub](https://github.com/atelierspierrot) repository and include 
it "as is" in your poject:

    $ git clone https://github.com/atelierspierrot/PACKAGE.git /path/of/your/project/deps

In this case, you will have to do the same for all its dependencies.

During the life-cycle of your project, to update the package, run:

    $ git remote update
    $ git rebase origin/CURRENT_BRANCH

#### Download a release tarball

You can also download an archive of a release from the repository (see the "releases" tab):

    $ wget --no-check-certificate -O PACKAGE-vX.Y.Z.tar.gz https://github.com/atelierspierrot/PACKAGE/archive/vX.Y.Z.tar.gz
    $ tar -xvf PACKAGE-vX.Y.Z.tar.gz

Our releases' tags are often signed with [our GPG key](http://keys.ateliers-pierrot.fr/).
To validate a tag, use the followings:

    $ wget http://keys.ateliers-pierrot.fr/ateliers-pierrot.asc
    $ gpg --import ateliers-pierrot.asc
    $ git tag --verify vX.Y.Z

#### Include the namespace

Then, to use the package classes, you just need to register its namespace and the one of
its dependencies with their corresponding directories using the *SplClassLoader* or
any other custom autoloader:

```php
require_once '.../src/SplClassLoader.php';
$loader = new SplClassLoader('PackageNamespace', '/path/to/package/src');
$loader->register();
// ... same process for dependencies
```

You can find the `PackageNamespace` name in the *autoload* entry of the `composer.json` 
manifest:

```json
"autoload": { 
    "psr-0": {
        "PackageNamespace": "src"
    }
}
```

### Using *Composer*

If you are a *Composer* user, just add the package to the requirements of your project's 
`composer.json` manifest file:

```json
"require": {
    "your-dependencies": "*",
    "atelierspierrot/PACKAGE": "version constraint ..."
}
```

You can also use the *Composer*'s command line interface directly:

    $ composer require atelierspierrot/PACKAGE:VERSION

The package will be automatically installed (and updated if so) with your classic composer's 
process (with its dependencies) and its namespace will be automatically added to the project's 
autoloader.

The best practice is to use a **release** of the package rather than a clone as it is light-weight
and cacheable by *Composer*. You can use a [version constraint](http://getcomposer.org/doc/01-basic-usage.md#package-versions) 
like `X.Y.Z` to choose a single version release, or a notation like `X.*` to use the latest 
release of the `X` major version.

If you don't mind about concerned major version, you can use the `@stable` shortcut to always
use the latest stable release.

If you need (or prefer) to use a clone of the package to get latest sources updates, 
you can still use the `dev-master` (for the "master" branch) or `dev-dev` (for the "dev" branch) 
version constraint.


Development
-----------

Our packages often embeds some configuration files to use development helper tools like
[Sami](https://github.com/FriendsOfPHP/Sami), a documentation generator, 
[PHP Mess Detector](http://phpmd.org/), a code analyzer and [PHP Unit](https://phpunit.de/),
a unit-tests manager.

To install these dependencies, you will need to use the `--dev` option of *Composer*:

    $ composer install --dev

### Using *Sami*

If the package embeds a `sami.config.php` file, it is ready to use *Sami*. The documentation
will often be generated in a `phpdoc/` directory at the root of the package.

To (re)generate this documentation, run:

    $ ./vendor/bin/sami.php render/update sami.config.php

Note that our configuration files are designed to use a `../tmp/cache/PACKAGE/` temporary
directory for this generation. This follows our "classic" development filesystem:

    DEV_PACKAGES_ROOT/              // a child of server's DOCUMENT_ROOT
    |------- PACKAGE1/
    |------- PACAKGE2/
    |------- ...
    |------- tmp/                   // a 775 directory for all temporary needs

The documentations of our last releases are available online at <http://docs.ateliers-pierrot.fr/>.

### Using *PHP Unit*

If the package embeds a `phpunit.xml.dist` configuration file, it is ready for unit testing.
To launch the tests, just run:

    $ ./vendor/bin/phpunit

If the package also embeds a `.travis.yml` dotfile, the tests are automated each time new
commits are pushed to the GitHub repository using the [Travis-CI](http://travis-ci.org/)
online tool. In this case, a button should be present in the header of the `README.md` file 
of the package that links to its tests page.

### Using *PHP Mess Detector*

The `phpmd` tool can analyze and point the mistakes of a PHP code, its naming or design.
To test a package, run: 

    $ ./vendor/bin/phpmd src/PackageNamespace/ text cleancode,codesize,controversial,design,naming,unusedcode

You can choose the test types to run by deleting one or more type(s) from last argument.


Development Environment
-----------------------

Our classic development environment allows usage of a global `autoload` linked to each
package's clone in a directory. 

    DEV_PACKAGES_ROOT/              // a child of server's DOCUMENT_ROOT
    |------- PACKAGE1/
    |------- PACAKGE2/
    |------- ...
    |------- bower.json             // the dev env Bower dependencies
    |------- composer.json          // the dev env Composer dependencies
    |------- ...
    |------- bin/                   // composer's installed binaries
    |------- vendor/                // composer's installed packages
    |------- tmp/                   // a 775 directory for all temporary needs

This ways, you can fallback to a global autoloader in `DEV_PACKAGES_ROOT/vendor/autoload.php`.

----

**(ↄ) 2008-2015 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France.
