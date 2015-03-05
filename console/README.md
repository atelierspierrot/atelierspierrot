Console
=======

This document explains the different console scripts distributed with this repository.
Related scripts are in `console/` directory.

Usage
-----

### PHP scripts

To use one of the PHP scripts, open a command line terminal and run:

    ~$ php path/to/repo/console/name-of-script ARGUMENTS
    
You can first try the `-h` option as argument to get help on the script.

If you have a terminal error or if the script doesn't seem to work, render it an
executable file running:

    ~$ chmod +x path/to/repo/console/name-of-script

and run your command again.

**Note** - All the PHP scripts are based on the `console_lib.php` library file present 
in the `console/` directory ; if you copy one of these scripts and paste
it in a new path, you will need to do so (*once*) with the library. If
the library seems to be missing, an error will be prompted.

### Shell scripts

To use of the shell scripts, open a command line terminal and run:

    $ ./path/to/console/script.sh ARGUMENTS


----
**Copyleft (ↄ) 2008-2015 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
