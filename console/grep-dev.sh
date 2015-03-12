#!/usr/bin/env bash
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

# help if no arg
if [ $# -eq 0 ]; then
    cat <<MESSAGE
###
Process a 'grep' search excluding specific dev environment directories and files
such as CVS directories, dependencies, modules and temporary files.

usage:      $0 '<search string>' [<search path = cwd>] [<grep options>]

options:    <search string>     : the pattern to search (requried)
            <search path>       : the path to search in (optional - defaults to 'pwd')
            <grep options>      : some options to add to the 'grep' search (optional)

###
MESSAGE
    exit 1
fi

# arguments
pattern="${1//\"/\\\"}"
shift
dir=$(cd "${1:-.}" && pwd)
shift
options="$*"

# grep construction
cd "$dir" && echo ">> $(pwd)" && \
"$(which grep)" --color \
    --exclude-dir="\.svn" --exclude-dir="\.git" --exclude-dir="\_*" \
    --exclude-dir="\.idea" --exclude-dir="\.buildpath" --exclude-dir="\.project" --exclude-dir="\.settings" \
    --exclude-dir=phpdoc --exclude-dir=vendor --exclude-dir=modules --exclude-dir=node_modules --exclude-dir=bower_components \
    --exclude-dir=tmp --exclude-dir=temp \
    --exclude=composer.lock --exclude="\~*" \
    -RnI $options "$pattern" . ;

# done
