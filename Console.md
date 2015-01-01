Console
=======

This document explains the different console scripts distributed with this repository.
Related scripts are in `console/` directory.


Usage
-----

To use one of the scripts, open a command line terminal and run:

    ~$ php path/to/repo/console/name-of-script ARGUMENTS
    
You can first try the `-h` option as argument to get help on the script.

If you have a terminal error or if the script doesn't seem to work, render it an
executable file running:

    ~$ chmod +x path/to/repo/console/name-of-script

and run your command again.

**Note** - All the scripts are based on the `console_lib.php` library file present 
in the `console/` directory ; if you copy one of these scripts and paste
it in a new path, you will need to do so (*once*) with the library. If
the library seems to be missing, an error will be prompted.


Manuals
-------

This section is just a copy of the man for each script.

### Directory recursive cleaner: `dir-cleaner`

    Presentation
        Shell script to delete a collection of specific operating systems or software internal files in a directory content and its sub-directories.
    
    Usage
        ~$ php dir-cleaner -[option[=value]]
    
    Basic options
        p=path             path to the working root directory, absolute path or relative to `cwd` (default is `.`, the current directory)
        c="coll ection"    the collection of filenames masks to delete (space separated, you must surround your list between double-quotes)
        d                  dry run: writes the list of matched files but doesn't execute the deletions
    
    Default files list
        ._*    .DS_Store    *~    Thumbs.db    ehthumbs.db    Icon
    
### GIT repositories manager: `git-repos-manager`

    Presentation
        Shell script to manage a local collection of GIT repositories from a webhost. Allowing to list distant repositories, create the collection clones, read the local collection informations, update the collection clones, delete the local clones and get the GIT status for each of them. You can specify a PCRE mask to filter working repositories list and define the local working path.
        (cURL and GIT binaries are required)
    
    Usage
        ~$ php git-repos-manager -[option[=value]]
    
    Basic options
        p=path      path to your local clones directory, absolute path or relative to `cwd` (default is `.`, the current directory)
        m=mask      filter the repositories that match the given mask (you must surround mask between double-quotes and may use PCRE valid expressions)
        a=action    which action to process in:
          l             list concerned available repositories from the host
          s             get the git status for local clones of all concerned repositories
          c             create a local clone of all concerned repositories
          r             read local clones informations for all concerned repositories
          u             update local clones of all concerned repositories
          d             delete local clones of all concerned repositories
    
    Hosting options
        w=webhost     the base URL of the web host (default is `https://api.github.com/`)
        u=username    the username in the host system
        t=usertype    the type of user in the host system (`user` or `org` for the default GitHub host)
        i=mask        the mask used to build webhost URL ; will be completed with `@webhost@`, `@username@`, `@usertype@` ; use with caution as this script is built to work with the GitHub API
    
    Examples
     - to list all available "CarteBlanche" repositories, run
        ~$ php git-repos-manager -a=l -m="carte-blanche*"
    
     - to create a clone of all bundles in the current directory, run
        ~$ php git-repos-manager -a=c -m="bundle"
        
    The best practice is to begin listing the repositories to be sure your filters are good and then process an action upon them.

### GIT changelog builder: `changelog-builder`

    Presentation
        Shell script to generate a changelog file info of a GIT repository in a BSD style.

    Usage
        ~$ php changelog-builder -[option[=value]] -g

    Options
        g             "GO": process the changelog generation ; option required (!)
        p=path        path to the working root directory, absolute path or relative to `cwd` (default is `.`, the current directory)
        f=filename    the filename of the generated changelog (default is `ChangeLog`)
        b=filename    the filename used to backup an existing changelog file (default is `ChangeLog.bak`)
        o             output: writes the changelog on screen instead of writing the file
        m=mode        the mode to use on an existing changelog file (`append`: add at the end, `prepend`: add at the beginning, `replace`: default)
        t=title       the title of the changelog (by default, the last tag name will be the title, or the current branch)

    Changelog string style

            Last tag found / GENERATION-DATE
            ================================
        
            2013-03-20
        
              * Commit title
                Author name <author@email> | full iso date | commit abbreviated hash
                  Commit message if so ...
        
              * ...
    
### GIT submodules database generator from `composer.json` dependencies: `submodules-from-packages-builder`

    Presentation
        Shell script to generate a `.gitmodules` file for a GIT repository of a package based on its Composer dependencies.

    Usage
        ~$ php submodules-from-packages-builder -[option[=value]]

    Options
        p=path        path to the working root directory, absolute path or relative to `cwd` (default is `.`, the current directory)
        f=filename    the filename defining the package (default is `composer.json`)
        t=filename    the target filename to populate (default is `.gitmodules`)
        b=filename    the filename used to backup an existing file (default is `.gitmodules.bak`)
        m=mode        the Composer mode to use for "require" statement (default is normal, can be "dev")
        o             output: writes the changelog on screen instead of writing the file
        i             init: run `git submodule init` after file creation to test its content
        g             "GO": process the changelog generation ; use this option to avoid viewing the help without any option

    Generated module block for each dependency

        [submodule "package/name"]
                path = package/path/in/project
                url = git source url


----
**Copyleft (ↄ) 2008-2015 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
