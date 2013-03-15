GIT ignore
=============

This document explains the default `.gitignore` file of our packages.

For more infos about `.gitignore` files, see <https://help.github.com/articles/ignoring-files>.


## Overview


    # Hack to allow keeping an empty directory
    !.gitkeep

    # OS & software files
    ._*
    .DS_Store
    *~
    Thumbs.db
    ehthumbs.db
    Icon

    # Les Ateliers specifics:
    
    # the composer lock file
    composer.lock

    # the documentation, that is NOT under version-control
    phpdoc/*

    # the external packages installed by Composer
    vendor/*

    # for development ...
    tmp/*
    dev/*



----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
