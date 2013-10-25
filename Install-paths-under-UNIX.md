Install paths under UNIX
========================

This document explains how and where UNIX users may install a new third-party command. By
"third-party" we intend "not natively distributed" or "developed by a guy in his garage" ;
the procedures and paths explained in this document DO NOT concern an internal system installation
and ARE NOT official. This is just a global "how-to" to explain and remind common usage.


Per user, for all users and global
----------------------------------

Using a UNIX system we have to keep in mind that it defines different scopes depending
on where a file is stored in the file system and the different rights he has. In our case,
we can ditinguish three scopes:

-   **"pre user"**: only the user who installed a thing will be able to access it and use
    it ; to remind, each user MAY have its own `$HOME` directory, which have a forbidden 
    access for other users ;
-   **"for all users"**: all users may use the thing, even if a single one installed it ;
-   **"global"**: all users may use the thing as it may be installed by a system administrator
    in a common path, accessible for everyone.

The basic difference between the "for all users" and "global" scopes are mostly in the way
the thing was installed and can be updated. The "for all users" can be installed by a single
and simple user, in a common path, and any other user in the same group as the 


### Binaries

    ~/bin  ==  $HOME/bin
    /usr/local/bin

### Configurations

    ~  ==  $HOME
    ~/.app-dir/  ==  $HOME/.app-dir/
    /usr/local/etc
    /usr/local/etc/.app-dir/


----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
