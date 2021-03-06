Les Ateliers Pierrot
====================

[![ateliers-pierrot.fr](http://img.ateliers-pierrot-static.fr/atelierspierrot-microbutton.svg)](http://www.ateliers-pierrot.fr/)

This repository contains various documentations & information about our work.


Web galaxy
----------

For a complete overview of our "rules" and some more information about our processes, 
see <http://spip.ateliers-pierrot.fr/nos-activites/notre-philosophie/>.

Our public GPG key is available at: <http://keys.ateliers-pierrot.fr/ateliers-pierrot.asc>.

For more information, here are some useful links of our web universe:

-   <http://www.ateliers-pierrot.fr/>: our homepage and <http://www.ateliers-pierrot.fr/>: our website,
-   <http://packagist.org/packages/atelierspierrot/>: our [Packagist](http://packagist.org/) profile,
-   <http://packagist.ateliers-pierrot.fr/>: our private packages manager (using [Satis](https://github.com/composer/satis)),
-   <http://trac.ateliers-pierrot.fr/>: our own sources tracker (using [InDefero](http://www.indefero.net/)),
-   <http://projects.ateliers-pierrot.fr/>: some demonstrations of our projects,
-   <http://sites.ateliers-pierrot.fr/>: some clones of our repositories,
-   <http://docs.ateliers-pierrot.fr/>: our packages documentations,
-   <http://mans.ateliers-pierrot.fr/>: our script's manual pages.

If you have a user access, here are our restricted websites:

-   <http://stats.ateliers-pierrot.fr/>: our frequenting tracker (using [Piwik](http://piwik.org/))

To follow us on the web:

-   <http://github.com/atelierspierrot>: our [GitHub](http://www.github.com/) profile,
-   <http://www.facebook.com/atelierspierrot>: our [Facebook](http://facebook.com/) profile
-   <http://twitter.com/AteliersPierrot>: our [Twitter](http://twitter.com/) profile


Packages review
---------------

The list below shows the global architecture of our PHP packages.

**We try to always sign our tag releases with [our GPG key](http://keys.ateliers-pierrot.fr/ateliers-pierrot.asc) 
to let you validate its authenticity.**

### Pure PHP stuff: the basics

#### The patterns

A set of PHP classic interfaces or abstract classes patterns to guide PHP developments.

- sources: <http://github.com/atelierspierrot/patterns>
- doc: <http://docs.ateliers-pierrot.fr/patterns/>
- packagist: <https://packagist.org/packages/atelierspierrot/patterns>

#### The global library

Our PHP library for everyday usage.

- sources: <http://github.com/atelierspierrot/library>
- demo: <http://sites.ateliers-pierrot.fr/library/>
- doc: <http://docs.ateliers-pierrot.fr/library/>
- packagist: <https://packagist.org/packages/atelierspierrot/library>

#### Validators

A PHP validators package to test RFC's compliance.

- sources: <http://github.com/atelierspierrot/validators>
- doc: <http://docs.ateliers-pierrot.fr/validators/>
- packagist: <https://packagist.org/packages/atelierspierrot/validators>

#### Webserver filesystem

Extending the SPL file system to manage webserver based file system (such as assets).

- sources: <http://github.com/atelierspierrot/webfilesystem>
- doc: <http://docs.ateliers-pierrot.fr/webfilesystem/>
- packagist: <https://packagist.org/packages/atelierspierrot/webfilesystem>

#### Media processing

A package to manipulate media files such as images.

- sources: <http://github.com/atelierspierrot/media-processing>
- demo: <http://sites.ateliers-pierrot.fr/media-processing/>
- doc: <http://docs.ateliers-pierrot.fr/media-processing/>
- packagist: <https://packagist.org/packages/atelierspierrot/media-processing>

#### Maths

Some PHP classes to do mathematics

- sources: <http://github.com/atelierspierrot/maths>
- demo: <http://sites.ateliers-pierrot.fr/maths/>
- doc: <http://docs.ateliers-pierrot.fr/maths/>
- packagist: <https://packagist.org/packages/atelierspierrot/maths>

#### Development & debug (under development)

A PHP Package to help development and debugging

- sources: <http://github.com/atelierspierrot/devdebug>
- demo: <http://sites.ateliers-pierrot.fr/devdebug/>
- doc: <http://docs.ateliers-pierrot.fr/devdebug/>
- packagist: <https://packagist.org/packages/atelierspierrot/devdebug>


### Assets stuff: the web-basics

#### Assets library

Our javascript/CSS library

- sources: <http://github.com/atelierspierrot/assets-library>
- demo: <http://sites.ateliers-pierrot.fr/assets-library/>
- doc: <http://docs.ateliers-pierrot.fr/assets-library/> (in progress)
- packagist: <https://packagist.org/packages/atelierspierrot/assets-library>

#### FamFamFam Silk icons sprites

The UNOFFICIAL sprites - Original icons work from www.famfamfam.com

- sources: <http://github.com/atelierspierrot/famfamfam-silk-sprite>
- demo: <http://sites.ateliers-pierrot.fr/famfamfam-silk-sprite/>
- packagist: <https://packagist.org/packages/atelierspierrot/famfamfam-silk-sprite>

#### FamFamFam Flags icons sprites

The UNOFFICIAL sprites - Original icons work from www.famfamfam.com

- sources: <http://github.com/atelierspierrot/famfamfam-flags-sprite>
- demo: <http://sites.ateliers-pierrot.fr/famfamfam-flags-sprite/>
- packagist: <https://packagist.org/packages/atelierspierrot/famfamfam-flags-sprite>

#### Gentleface icons sprites

The UNOFFICIAL sprites - Original icons work from www.gentleface.com

- sources: <http://github.com/atelierspierrot/gentleface-sprites>
- demo: <http://sites.ateliers-pierrot.fr/gentleface-sprites/>
- packagist: <https://packagist.org/packages/atelierspierrot/gentleface-sprites>


### Third-party plugins

#### Assets manager (Composer plugin)

A Composer plugin to manage "assets" package type

- sources: <http://github.com/atelierspierrot/assets-manager>
- doc: <http://docs.ateliers-pierrot.fr/assets-manager/>
- packagist: <https://packagist.org/packages/atelierspierrot/assets-manager>

#### Assets bootstrapper (Bootstrap + Font Awesome)

The Bootstrap & Font-Awesome libraries ready to use with Assets-Manager

- sources: <http://github.com/atelierspierrot/assets-bootstrapper>
- demo: <http://sites.ateliers-pierrot.fr/assets-bootstrapper/>
- packagist: <https://packagist.org/packages/atelierspierrot/assets-bootstrapper>


### Framework packages

#### Internationalization

A PHP package to manage i18n: translations, pluralizations and date and number formats according to a localization

- sources: <http://github.com/atelierspierrot/internationalization>
- demo: <http://sites.ateliers-pierrot.fr/internationalization/>
- doc: <http://docs.ateliers-pierrot.fr/internationalization/>
- packagist: <https://packagist.org/packages/atelierspierrot/internationalization>

#### MIME mailer

A PHP class to send rich MIME emails.

- sources: <http://github.com/atelierspierrot/mime-mailer>
- demo: <http://sites.ateliers-pierrot.fr/mime-mailer/>
- doc: <http://docs.ateliers-pierrot.fr/mime-mailer/>
- packagist: <https://packagist.org/packages/atelierspierrot/mime-mailer>

#### GIT API

A PHP API to get infos and manage a GIT distant or local repository

- sources: <http://github.com/atelierspierrot/git-api>
- demo: <http://sites.ateliers-pierrot.fr/git-api/>
- doc: <http://docs.ateliers-pierrot.fr/git-api/>
- packagist: <https://packagist.org/packages/atelierspierrot/git-api>

#### Webservices

A PHP engine to manage web-services easily

- sources: <http://github.com/atelierspierrot/webservices>
- demo: <http://sites.ateliers-pierrot.fr/webservices/>
- doc: <http://docs.ateliers-pierrot.fr/webservices/>
- packagist: <https://packagist.org/packages/atelierspierrot/webservices>

#### Template engine

A PHP package to build HTML5 views (based on HTML5 Boilerplate layouts)

- sources: <http://github.com/atelierspierrot/templatengine>
- demo: <http://sites.ateliers-pierrot.fr/templatengine/>
- doc: <http://docs.ateliers-pierrot.fr/templatengine/>
- packagist: <https://packagist.org/packages/atelierspierrot/templatengine>

#### Web syndication analyzer

A PHP package to manipulate RSS feeds.

- sources: <http://github.com/atelierspierrot/web-syndication-analyzer>
- demo: <http://sites.ateliers-pierrot.fr/web-syndication-analyzer/>
- doc: <http://docs.ateliers-pierrot.fr/web-syndication-analyzer/>
- packagist: <https://packagist.org/packages/atelierspierrot/web-syndication-analyzer>

#### Cryptography

A set of PHP classes to crypt and decrypt.

- sources: <http://github.com/atelierspierrot/cryptography>
- demo: <http://sites.ateliers-pierrot.fr/cryptography/>
- doc: <http://docs.ateliers-pierrot.fr/cryptography/>
- packagist: <https://packagist.org/packages/atelierspierrot/cryptography>


### Applications

#### CarteBlanche

A simple PHP framework

- sources: <http://github.com/php-carteblanche/carteblanche>
- demo: <http://sites.ateliers-pierrot.fr/carteblanche/>
- doc: <http://docs.ateliers-pierrot.fr/carteblanche/>
- packagist: <https://packagist.org/packages/carte-blanche/carte-blanche>

#### DocBook

Simple CMS PHP app to build rich HTML5 views from a Markdown contents filesystem

- sources: <http://github.com/atelierspierrot/docbook>
- demo: <http://sites.ateliers-pierrot.fr/docbook/>
- doc: <http://docs.ateliers-pierrot.fr/docbook/>
- packagist: <https://packagist.org/packages/atelierspierrot/docbook>


### Composer instructions

To install all our packages in a `composer.json` configuration file, use:

    "require": {
        "php": ">=5.3.2",
        "atelierspierrot/patterns": "1.*",
        "atelierspierrot/library": "1.*",
        "atelierspierrot/validators": "1.*",
        "atelierspierrot/maths": "1.*",
        "atelierspierrot/mime-mailer": "1.*",
        "atelierspierrot/webfilesystem": "1.*",
        "atelierspierrot/media-processing": "1.*",
        "atelierspierrot/devdebug": "1.*",
        "atelierspierrot/demobuilder": "1.*",
        "atelierspierrot/assets-manager": "1.*",
        "atelierspierrot/templatengine": "1.*",
        "atelierspierrot/assets-library": "1.*",
        "atelierspierrot/assets-bootstrapper": "1.*",
        "atelierspierrot/famfamfam-silk-sprite": "1.*",
        "atelierspierrot/famfamfam-flags-sprite": "1.*",
        "atelierspierrot/gentleface-sprites": "1.*",
        "atelierspierrot/webservices": "1.*",
        "atelierspierrot/internationalization": "1.*",
        "atelierspierrot/git-api": "1.*",
        "atelierspierrot/cryptography": "1.*@dev",
        "atelierspierrot/web-syndication-analyzer": "1.*@dev"
    },
    "repositories": [
        { "type": "composer", "url": "http://packagist.ateliers-pierrot.fr/" }
    ]


----

**(ↄ) 2008-2015 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France.
