GIT ignore
=============

This document explains the default `.gitignore` file of our packages.

For more infos about `.gitignore` files, see <https://help.github.com/articles/ignoring-files>.


## Overview

Below is the default `.gitignore` we use in our packages. A sample file is embeded in the
package at <commons/gitignore.txt>.

    # Hack to allow keeping an empty directory
    !.gitkeep

    # OS & software files
    .DS_Store
    .AppleDouble
    .LSOverride
    .Spotlight-V100
    .Trashes
    Icon
    ._*
    *~
    *~lock*
    Thumbs.db
    ehthumbs.db
    Desktop.ini

    # Les Ateliers specifics:

    # the composer lock file
    composer.lock

    # user defined files
    user/*

    # the external packages installed by Composer, Template Engine or others
    vendor/*
    bin/vendor/*
    data/vendor/*
    src/vendor/*
    www/vendor/*

    # the documentation, that is NOT under version-control by default
    # this may be commented on the "dev" branch
    phpdoc/*

    # for development ...
    # this may be commented on the "wip" branch
    dev/*
    build/*

    # any "tmp*" file or dir
    tmp*
    bin/tmp*
    demo/tmp*
    data/tmp*
    user/tmp*
    src/tmp*
    www/tmp*

To use our `commons/gitignore.txt` default file, just run:

    ~$ cp path/to/atelierspierrot/commons/gitignore.txt path/to/git/project/.gitignore


## Exclude files globaly

As explained on the [GitIgnore page of GitHub](http://github.com/github/gitignore), you
can define some files excluded for all git projects on a device running:

    ~$ git config --global core.excludesfile your_file/path

A sample file is embeded in the package at <commons/gitignore-global.txt>. To use it on your
machine (installing it at your `$HOME` directory root), run:

    ~$ cp path/to/atelierspierrot/commons/gitignore-global.txt ~/.gitignore_global
    ~$ git config --global core.excludesfile ~/.gitignore_global

Below is our current `gitignore_global` content:

    # OS & software files
    .DS_Store
    .AppleDouble
    .LSOverride
    .Spotlight-V100
    .Trashes
    Icon
    ._*
    *~
    *~lock*
    Thumbs.db
    ehthumbs.db
    Desktop.ini


----
**Copyleft (c) 2008-2014 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
