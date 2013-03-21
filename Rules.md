Rules
=============

This document explains the coding, storing and naming rules we use in our packages.


## Coding standards & naming rules

To build a comprehensive and maintainable code, we try to follow the coding standards and
naming rules most commonly in use:

-   the [PEAR coding standards](http://pear.php.net/manual/en/standards.php)
-   the [PHP Framework Interoperability Group standards](https://github.com/php-fig/fig-standards).

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
    | README.md
    | src/
    | ---- package-namespace/
    | ---------------------- class-name.php
    | bin/
    | ------ binary-script.php
    | phpdoc/


----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
