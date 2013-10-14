Rules
=============

This document explains the coding, storing and naming rules we use in our packages.


## Coding standards & naming rules

To build a comprehensive and maintainable code, we try to follow the coding standards and
naming rules most commonly in use:

-   the [PEAR coding standards](http://pear.php.net/manual/en/standards.php)
-   the [PHP Framework Interoperability Group standards](http://github.com/php-fig/fig-standards).

Knowing that, all classes of our packages are named and organized in an architecture to
allow the use of the [standard SplClassLoader](https://gist.github.com/jwage/221634).

As explained in the [Dependencies](Dependencies.md) document, if you include one of our
packages using [Composer](http://getcomposer.org/), all the package namspaces are 
automatically integrated in the autoloader. In this case, you have nothing to do to load
the classes but define the `use` statement in your scripts.


## Packages architecture

A common architecture for one of our PHP packages should be something like:

    | composer.json
    | sami.config.php
    | phpunit.xml.dist
    | .travis.yml
    | README.md
    | LICENSE
    |
    | bin/
    | ---- binary-script.php
    | ---- binary-script.sh
    |
    | data/
    | ----- data-file.csv
    |
    | demo/
    | ----- demo-page.html
    |
    | doc/
    | ---- documentation-file.md
    |
    | config/
    | ---- config-file.ini
    |
    | phpdoc/
    | ------- automatic PHP documentation ...
    |
    | src/
    | ---- PackageNamespace/
    | ---------------------- ClassName.php
    |
    | tests/
    | ---- unit-tests.php
    |
    | user/
    | ----- user-config.ini
    | ----- UserClass.php
    |
    | var/
    | ---- variable-file.txt
    | ---- history.log
    |
    | www/
    | ---- assets/
    | ------------ script-file.js
    | ------------ styles-file.css
    | ---- index.php

For those who know a linux kernel, we mostly try to follow the linux architecture.

Any third-party dependency installed by Composer, our internal [Template Engine](http://github.com/atelierspierrot/templatengine)
or any other package installer are stored in a `vendor/` directory or sub-directory ;
you may not modify its contents.

Any temporary file such as cached files or other environment dependent files are stored in a
`tmp/` directory or sub-directory. Any temporary file NOT stored in a `tmp/` directory will
be named `tmp*` (where the asterisk may be anything). All these contents are excluded from
version control and can be deleted with (we hope so) no incidence for the project.

### Overview

-   `bin/` contains the application console, its installer/updater and third-party shell scripts;

-   `data/` contains data files like CSV;

-   `demo/` contains the HTML demonstration of the package;

-   `doc/` contains some documentation files, mostly in Markdown format;

-   `config/` contains some configuration files;

-   `src/` contains the PHP sources of the application and the template files;

-   `user/` is the directory to put your own user configuration or templates (*see the 
    [Fallback system](#fallback-system) section for more infos*) ;

-   `var/` contains variable files (*that should be re-written*);

-   `www/` sub-directory must be the `DOCUMENT_ROOT` of your virtual host (*anything outside 
    this directory is not used in HTML pages*);
    
### Fallback system

Our packages are constructed, when it is possible and relevant, to allow user to override
some configuration settings and the templates used for pages building.

This feature is quite simple: any file found in the `user/XXX/` directory will be taken
primary to the default package file, where `XXX` is the relative path of the original file
in the package.
    

----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
