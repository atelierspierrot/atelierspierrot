#!/bin/bash

# list of paths to cleanup on master
CLEANUP=(jsdoc build dev DEVNOTES.md)

# get arg step
STEP=$(echo "$*" | sed 's/.*\-\-step=\([^ ]*\).*/\1/')
if [ "$STEP" = "$*" ]; then STEP=''; fi
if [ ! -z "$STEP" ] && [ "$STEP" != '1' ] && [ "$STEP" != '2' ] && [ "$STEP" != '3' ]; then
    echo "> '--step' mut be null or in 1,2,3 !"
    exit 1
fi

# copy this script in tmp
_TMPSCRIPT="tmp/$(basename $0)"
if [ ! -f "$_TMPSCRIPT" ]; then
    mkdir -p tmp
    cp "$0" tmp
    echo "> you must now run: ./${_TMPSCRIPT} $*"
    exit 1
fi

# actually merge dev
if [ -z "$STEP" ]||[ "$STEP" = '1' ]; then
    git checkout master
    git merge dev --no-ff
    if [ $? -ne 0 ]; then
        echo "> you must resolve conflicts and then run: ./${_TMPSCRIPT} $* --step=2"
        exit 1
    fi
fi

# cleanup
if [ -z "$STEP" ]||[ "$STEP" = '1' ]||[ "$STEP" = '2' ]; then
    git rm -rf "${CLEANUP[@]}"
    if [ $? -ne 0 ]; then
        echo "> you must resolve conflicts and then run: ./${_TMPSCRIPT} $* --step=3"
        exit 1
    fi
fi

# commit cleaning
git commit -a -m "automatic cleanup of master"

# ok
echo "OK > master is up-to-date and cleaned up!"
