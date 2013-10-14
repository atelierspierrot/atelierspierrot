Package Deployment
==================

This document explains how to deploy a package, a new version tag and globally how to manage
a GIT package to avoid common errors.

All procedures described below are programmed in our [Dev Tools](http://github.com/atelierspierrot/dev-tools)
package which can automatically handle all of them.


Clean a repository
------------------

The first thing to do before commiting or making a new version tag is to ensure all files
to commit or to add in the tag are wanted and are defined with the good UNIX rights.

To clean all OS or IDE files that we don't want to add in a package sources, you can run
something like:

    CLEANUP_FILES=( .DS_Store .AppleDouble .LSOverride .Spotlight-V100 .Trashes Icon \
        ._* *~ *~lock* Thumbs.db ehthumbs.db Desktop.ini .project .buildpath );
    for F in "${CLEANUP_FILES[@]}"; do find . -type f -name $F -exec rm {} \; done

To set all directories rights on `0755` and all files rights on `0644`, run:

    find . -type d -exec chmod 0755 {} \;
    find . -type f -exec chmod 0644 {} \;

Doing so, keep in mind that some scripts like shell scripts required to be `+x` for everyone.
To do so, run:

    find . -type f -name *.sh -exec chmod a+x {} \;


Create a new version tag
------------------------

To create a new version git tag, commit all your changes and run something like:

    git tag -a TAG_NAME -m "My commit message" && git push origin TAG_NAME

Keep in mind that `TAG_NAME` MUST be something like `vX.Y.Z` and increase any existing tag.


Deploy a project on a distant server
------------------------------------

### Authentication

To deploy a project on a distant server, you will need to inform him about your SSH public key.
To do so, run:

    ssh-copy-id -i ~/.ssh/id_rsa.pub user@server.example.org

If it doesn't work, try:

    scp ~/.ssh/id_rsa.pub user@server.example.org:.ssh/authorized_keys2

If you don't have a public/private SSH keys pair yet, create it running:

    ssh-keygen -t rsa

### Synchronization

Once the server knows your public key, you can use `scp` or `rsync` to synchronize your project
with its distant target:

    rsync -avrlzh ~/project/path/... -e ssh user@server.example.org:~/www/...

You can first use the `--dry-run` option to see what will be synchronized.

To exclude a file or a directory contents from the synchronization, user the `--exclude` option
(you can use it more than once):

    rsync -avrlzh ~/project/path/... \
        --exclude dir/one --exclude dir/file.txt \
        -e ssh user@server.example.org:~/www/...

### Deployment

To deploy some environment specific files, you can use the "suffixed files" solution:

-   create one version of the file for each environment, naming each copy with the same name
    suffixed by something like `__ENV__`
-   once you have synchronized your files for deployment, just copy all files suffixed with
    concerned environment running:
    
        suffix="__PROD__" && find . -name "*${suffix}" -exec sh -c 'cp -v "$1" "${1%%$2}"' _ {} "${suffix}" \;


----
**Copyleft (c) 2008-2013 [Les Ateliers Pierrot](http://www.ateliers-pierrot.fr/)** - Paris, France - Some rights reserved.

Scripts are licensed under the [GNU General Public License version 3](http://www.gnu.org/licenses/gpl.html).

Contents are licensed under the [Creative Commons - Attribution - Share Alike - Unported - version 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.
