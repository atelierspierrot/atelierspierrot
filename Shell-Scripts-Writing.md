Shell scripts writing
=====================

This document describes the best practices to write shell scripts.


Information messages
--------------------

Any shell script MUST define different messages levels to inform user about its usage. The
best practice is to distinguish three types of messages:

-   the **help** string, very short, which may just be a basic reminder of the script synopsis
    like:
    
        usage: script-name [--option1] [-opt2 =value] [argument1] [argument2] ...
    
    This information should be all visible in a single screen (no pager or scrolling) and
    may be displayed in case of error or missing option. Its goal is to let the user know
    in one sight where he is doing wrong and how to correct his command.

-   the **usage** string, longer, which may develop the role of each option, its default
    value, the aliases defined and any information that may help the user to understand
    how to get the wanted result to work. As this information may be quite long, it is 
    a good idea to open it with programs like `less` or `more` to use an internal pager.

-   the **manual** information, opened with the `man` program, which may be the final,
    literal and complete usage information of the script, with all useful information about
    its options or arguments, some examples, some links to its sources or updates, a contact
    information and the way to inform about bugs.

These three informations levels may be accessed from the original script using evident 
option or argument like, respectively, `help`, `usage` and `man`. The manual page could
follow its own rule as it will often be opened running `man my-manual` ; so it is not 
mandatory to let it accessible by a script option. In this case, the help or usage strings
MAY inform user about the existence of the manual page.


Common options
--------------




----
**Copyleft (c) 2008-2014 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
