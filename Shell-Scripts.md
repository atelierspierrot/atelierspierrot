Shell scripts
=============

This document explains how Les Ateliers constructs their shell scripts.
Related scripts are in `console/` directory.


Usage
-----

All our shell script have the extension `sh` ; they may be runnable by the system, if this is
not the case, run:

    ~$ find path/to/repo/console/ -name "*.sh" -exec chmod +x {} \;

To use one of the shell scripts, run:

    ~$ sh path/to/repo/console/name-of-script.sh -[options [=argument]]
    
You can first try the `-h` option as argument to get help on the script.


Common options
--------------

All our scripts can be run with options, some common options used for all of our scripts and
some specific options for each one.

These common options are:

-   `-h`: "help" - get a usage information about the script, its usage and options,
-   `-v`: "verbose" - increase in certain cases script verbosity writing informations on screen,
-   `-q`: "quiet" - decrease verbosity of the script ; only errors and commands rendering will be written on screen,
-   `-i`: "interactive" - ask for confirmation before any action like creation, edition or deletion,
-   `-x`: "debug" - some commands will be written on screen but not executed.

For a new script development using these options, you can start on a copy of the model file `commons/model.sh.txt`.


Options usage
-------------

As a reminder of the common shell scripts options usage, you can:

-   group simple options in one item like `-abc` instead of `-a -b -c`,
-   mix grouped options and ungrouped ones, like `-ab -c`,
-   specify an argument if necessary, like: `-ab -c argument` or `-abc argument`
-   use the `--` sign to explicitly mark the end of the current script options, like:
    `-ab -c=argument -- -d`, where option `d` will not be considered.

And, for our scripts, you can also:

-   use an equal sign between the option and its argument, like: `-ab -c=argument` or `-abc=argument`


Common behaviour
----------------

The script model embeds a library of shell functions to construct an homogeneus script execution
and user information. Have a look in the `library` part of [the model](commons/model.sh.txt) for
more informations about these functions.

### User informations

Our shell scripts uses a specific way of messages formating to identify the information type
and display usefull and comprehensible screen infos.

A basic message template should be:

    X  >> info

where `X` can be a ponctuation sign to identify the information type, like `!` for error and
warnings, `?` for a prompt ...



----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
