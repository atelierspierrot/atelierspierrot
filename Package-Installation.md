Package Installation
=============

This document explains how to install one of our packages, include it in your work and
use it in your code.


You can install and include one of our packages in many ways:

-   make a clone of the package's [GitHub](http://github.com/atelierspierrot) repository
-   download an archive of the package's sources from the [GitHub](http://github.com/atelierspierrot) repository
-   use our package's "packagist" from [Composer](https://packagist.org/packages/atelierspierrot/) 


Clone a repository
-------------------

First, you can clone the package's [GitHub](http://github.com/atelierspierrot) repository
and include it "as is" in your poject. This solution allows you to follow sources updates
running a `checkout` on your clone regularily.

To clone a repository just run :

    ~$ git clone git://github.com/atelierspierrot/PACKAGE_NAME.git path/to/your/project
    Cloning into ...
    ...

If nothing went wrong, you will finally have a full copy of the repository with a `.git/`
directory meaning that your version is under version-control.

See the [Including namespaces](#namespaces) section to learn how to include the package's
scripts in your project.


Download an archive
-------------------

You can also download an archive of the package repository from [GitHub](http://github.com/atelierspierrot).
To do so, just go to the GitHub homepage of the repository and click on the "ZIP" button. This way,
you will have the last version of the "master" branch.

If you prefer to make this download from the command line, you can run:

    ~$ wget --no-check-certificate https://github.com/atelierspierrot/PACKAGE_NAME/archive/master.tar.gz
    ~$ tar -xvf master.tar.gz
    ~$ cp -r PACKAGE_NAME path/to/your/project/

If some are availables, it is a good practice to download a TAG from the GitHub repository as
they may be a stable version. To have a list of tags, browse to:

    http://github.com/atelierspierrot/PACKAGE_NAME/tags

See the [Including namespaces](#namespaces) section to learn how to include the package's
scripts in your project.


Using Composer
--------------

If you use [Composer](http://getcomposer.org/) to manage your project's dependencies, including it
is as easy as adding to your `composer.json` file:

    "require": {
        #...
        "atelierspierrot/PACKAGE_NAME": "dev-master"
    },

Some of our packages are registered in the [Packagist](https://packagist.org/packages/atelierspierrot/)
so that you don't need to do more than adding the package name to your "rqeuirements". For
all other packages, you will need to add the [GitHub](http://github.com/atelierspierrot)
to the `repositories` entry of your `composer.json` manifest like:

    "repositories": [
        #...
        { "type": "vcs", "url": "http://github.com/atelierspierrot/PACKAGE_NAME" }
    ],

In certain cases, you can create a project directly from Composer running:

    ~$ php path/to/composer.phar create-project atelierspierrot/PACKAGE_NAME path/to/your/project dev-master --no-dev

You can select a specific version replacing `dev-master` by the version number.


Including namepsaces {#namespaces}
----------------------------------

As explained in the [Rules](Rules.md) document, we almost try to follow the classic naming
rules in PHP coding: each package as its own namespace and every class in the package
follows the standard naming rule.

For example, if your want to read the `Patterns\Interfaces\ArrayInterface` code, you will
find it in file `src/Patterns/Interfaces/ArratInterface.php` in the package.

So, to use some package's classes in your work, you just need to register their namespaces directories
using the [SplClassLoader](https://gist.github.com/jwage/221634) or any other custom autoloader.
A copy of the "SplClassLoader" is often included in the packages, in the `src/` directory.

For example, to register the `WebFilesystem` namespace, you need to write:

    require_once '/path/to/package/src/SplClassLoader.php';
    $classLoader = new SplClassLoader('WebFilesystem', '/path/to/package/src');
    $classLoader->register();

If you included one of our packages using [Composer](http://getcomposer.org/) as explained above,
all the package namspaces are automatically integrated in the autoloader. In this case, 
you have nothing to do to load the classes but define the `use` statement in your scripts.


----
**Copyleft (c) 2008-2014 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
