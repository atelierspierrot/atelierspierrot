#!/bin/bash
#
# Copyleft (ↄ) 2008-2015 Pierre Cassat and contributors
# <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.
#
# The source code of this package is available online at 
# <http://github.com/atelierspierrot/atelierspierrot>.
#

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
