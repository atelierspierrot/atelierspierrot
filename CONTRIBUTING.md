Contribute to one of our packages
=================================


If you want to contribute to one of our projects, first you are very welcome ;) Then, this documentation
file will introduce you the "dev-cycle" of a package.


Inform about a bug or request for a new feature
-----------------------------------------------

### Bug ticketing

A bug is a *demonstrable problem caused by the code* (and not due to a special user behaviour).
Bugs are inevitable and exist in all software. If you find one and want to transmit it, first
**it is very helpful** as it will participate to build a robust software.

But ... a bug report is helpful as long as it can be understood, reproduced, and that it permits to
identify the error (and what caused it). A good bug report MUST follow these guidelines:

-   **first**: search in the issue tracker if your bug has not been transmitted yet ; if you find it,
    you can add a new comment to the appropriate thread with your experience if it seems different
    from the others ;
-   **then**: check if it exists right now: try to reproduce it with the current code to confirm it still exists ;
-   if you **finally** create a bug ticket, try to detail it as much as possible:
    -   what is your environment (application version, OS, device ...)?
    -   describe and comment the steps that brought you to that bug
    -   try to isolate the problem as much as possible
    -   what did you expect?

The ticketing manager of a package is available at `http://github.com/patelierspierrot/<package-name>/issues`.

### Feature requests

If you want to ask for a new feature, please follow these guidelines:

-   the goal of our projects is to be (and keep) relevant for a large public ; maybe your request
    is quite personal (you have a particular need) and can be discussed with us by email ; in this
    case please do not make a feature request (!)
-   if you think something is missing or have an idea to increase one of a packages' features, then
    you are ready for a "feature request" ; you can create an issue ticket beginning its name by
    "feature request: " ; please detail your request or your idea as much as possible, with a lot 
    of your experience.


Actually change the code
------------------------


First of all, you may do the following two things:

-   read the *How to contribute* section below to learn about forking, working and pulling,
-   from your fork of the repository, switch to the `dev` branch: this is where the dev things are done.


### How to contribute ?

If you want to correct a typo or update a feature of a package, the first thing to do is
[create your own fork of the repository](http://help.github.com/articles/fork-a-repo).
You will need a (free) GitHub account to do this and your copy will appear in your forks list.
This is on THIS repository (your own fork) that you will work (you have no right to make 
direct `push` on the original repository).

Once your work seems finished, you'll have to commit it and push it on your fork (you may 
finally see your modifications on the sources view on GitHub). Then you'll have to make a 
"pull-request" to the original repository, commenting it with a description of your correction or
update, or anything you want us to know about ... Then, if your work seems ok for us 
(and it certainly will :) and when we'll have the time (!), your work will finally be 
"merged" in the original repository and you will be able to (eventually) close your fork. 
Note that the "merge" of a pull-request keeps your name and profile as the "commiter" 
(the one who made the stuff).

**BEFORE** you start a work on the code, please check that this has NOT been done yet, or part
of it, by giving a look at `http://github.com/patelierspierrot/<package-name>/pulls`. If you 
find a pull-request that seems to be like the modification you were going to do, you can 
comment the request with your vision of the thing or your experience.


### Full installation of a fork

To prepare a development version of a package, clone your fork of the repository and
put it on the "dev" branch:

    git clone http://github.com/<your-username>/<package-name>.git
    cd <package-name>
    git checkout dev

Then you can create your own branch with the name of your feature:

    git checkout -b <my-branch>

If the development process of the package requires some external dependencies to work, you
must install them first.

If the root directory of the package contains a `composer.json` manifest file, some dependencies
are loaded via [Composer](http://getcomposer.org/). To install them, run:

    // install Composer if you don't have it in your system
    curl -sS https://getcomposer.org/installer | php

    // install PHP dependencies
    php composer.phar install

If the root directory of the package contains a `bower.json` manifest file, some dependencies
are loaded via [Bower](http://bower.io/). To install them, you may first 
[install Bower in your system](http://bower.io/#install-bower), and then run:

    // install assets dependencies
    bbower install

If the root directory of the package contains a `.gitmodules` configuration file, some dependencies
are managed as [GIT submodules](http://git-scm.com/book/en/Git-Tools-Submodules). To install them,
run:

    // install submodules
    git submodule init
    git submodule update

Finally, your clone is ready ;)

You can *synchronize* your fork with current original repository by defining a remote to it
and pulling new commits:

    // create an "upstream" remote to the original repo
    git remote add upstream https://github.com/atelierspierrot/<package-name>.git

    // get last original remote commits
    git checkout dev
    git pull upstream dev


Coding rules
------------

-   use spaces (no tab) ; 1 tab = 4 spaces ; this is valid for all languages
-   comment your work (just enough)
-   CSS files:
    -   try to align the opening brackets for reading comfort
-   JS scripts:
    -   consider the `"use strict"` statement
    -   always end your lines of code by a semicolon `;`
-   PHP scripts:
    -   in case of error, ALWAYS throw `Exception`s or `ErrorException`s (specific ones if available)
        with a message
-   Shell scripts:
    -   any bash function must return a status (e.g. `return 0`)


----

If you have questions, you can (eventually) contact us at *contact [at] ateliers [dash] pierrot [dot] fr*.

----

**(ↄ) 2008-2015 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France.
