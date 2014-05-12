Changelog
=========

This document explains the rules followed by Les Ateliers to write a Changelog and the
commit messages.

A shell script is proposed with the [Console](Console.md) objects.


Commit messages
---------------

Our commit messages must follow this construction rule:

    A short title for the commit changes (less than 80 characters)
    
    And, after a blank line, the full description of the changes, that can be ...
    
    ... on multiple lines ...
    
    And contains an infos like:
    Bug: URL or ID
    Fix #ID

To open a text editor from command line to fill your message just run:

    ~$ git commit


Changelog
---------

The rules are inspired (*but are not really following*) the [GNU projects Changelog rules](http://www.gnu.org/prep/standards/html_node/Change-Logs.html#Change-Logs).

To write a Changelog information files, it is important to identify the version concerned.
So we may separate each changes blocks by declared versions, such as tags as long as they
are defined for each major AND minor new version. The result should be something like:

    version X.Y.Z(n) - date
    =======================
    
        the list of changes from old version to this one
    
    version X.Y.Z(n-1) - date
    =========================

        ...

A list of changes is a list of commits, organized by date showing for each commit: its title,
its author, the commit date and time, its abbreviated hash and the full commit message. Each
line of the message must be less than 80 characters (*it must be cut if necessary*).

    2013-03-29

      * test for commit message rules
        Piero Wbmstr <piero.wbmstr@gmail.com> | 2013-03-29 23:39:08 +0100 | 262e522
          mqlskdjf jkqmsldfjqmlsdkjf jkMLkjqk sdjfmlqksdjfmlqksdjmflk JKMlkjqks
          djfmqlsdkfjmLKJmlkjqsdf
          
          qsdfmlkj JKLmkj jmlkjqsdf
          
          See bug: #2345   

      * Including a Profiler in the global template with page and system infos
        PïeroWbmstr <piero.wbmstr@gmail.com> | 2013-03-29 11:01:25 +0100 | 55d0e02
        

      * wip ...
        Piero Wbmstr <piero.wbmstr@gmail.com> | 2013-03-29 09:24:35 +0100 | 651630a
        

    2013-03-28

      * Oups, a forgotten template for search results
        PïeroWbmstr <piero.wbmstr@gmail.com> | 2013-03-28 12:04:27 +0100 | 091dab7



----
**Copyleft (c) 2008-2014 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
