GIT Repository
==============

This document explains how we manage the GIT repositories of our packages.

For more infos about GIT, see <http://git-scm.com/>.

For more infos about GitHub, see <http://github.com/about>.

For a list of our packages on GitHub, see <http://github.com/atelierspierrot>.


Common Architecture
-------------------

All our packages are developped under a GIT version-control repository hosted by 
[GitHub](http://github.com/about). This way, any update can be followed on a timeline
and you can go back to an old version if needed.

All our repositories follows an architecture like:

-   **branch "master"**: this is the master stable actual version of the package ; it does
    NOT include the documentation (*if so*) and is the branch used by "Packagist" ;
-   **branch "dev"**: this is the mirror of the "matser" branch for developments ; it can be quite
    ahead from the "master" one by some commits not yet merged in "master" (*but with tested evolutions*)
    and DOES include the documentation ; in some cases, it also includes some "build" stuff ;
-   **branch "wip"**: this is the development branch of the package ; it may be ahead from
    the "master" branch by some commits not yet merged in "master" because not yet tested ;
    this is the real work-in-progress and may NOT be used in your projects.


Versioning
----------

You try to follow the [Semantic Versioning](http://semver.org/) rules to build our releases
references. Which means that any release is named like `X.Y.Z(-status)` where:

-   `X` is the major version number ; it may change only when the application API changes
-   `Y` is the minor version number ; it may be incremented for each new release of a major version,
    as long as the changes are not a bug fix
-   `Z` is the last bugfix reference ; it may only change if a bug has been identified and
    fixed ; this king of update MUST be attached to a bug ticket
-   the `status` usually informs about a pre-release state: `alpha`, `beta` etc ; it remains
    on `dev` for our development branches (such as the `wip` one as explained above).


Open-Source & Community
-----------------------

Our work are free softwares, mostly available under [General Public License version 3.0](http://opensource.org/licenses/GPL-3.0) ; 
you can freely use it, for yourself or a commercial use, modify its source code according to your needs, 
freely distribute your work and propose it to the community, as long as you let an information about its first author.

As the sources are hosted on a [GIT](http://git-scm.com/) repository on [GitHub](http://github.com/atelierspierrot),
you can modify it, to ameliorate a feature or correct an error, by [creating your own fork](https://help.github.com/articles/fork-a-repo)
of the repository, modifying it and [asking to pull your modifications](https://help.github.com/articles/using-pull-requests) on
the original branch.

Please note that the "master" branch is **always the latest stable version** of the code. 
Development is done on branches "dev" or "wip" and you can create a new one for your own developments.


----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
