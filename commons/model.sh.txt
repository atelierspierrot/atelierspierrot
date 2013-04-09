#!/bin/bash
#
# Copyleft (c) 2013 Pierre Cassat and contributors
# <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
# License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
# Sources <https://github.com/atelierspierrot/atelierspierrot>
# 
# console/name-of-script.sh
#
# For more infos run:
# 
#     ~$ sh path/to/name-of-script.sh -h
# 
# Or see: <https://github.com/atelierspierrot/atelierspierrot/Shell-Scripts.md>
# 

#### script settings ##########################

VERSION="0.0.1-dev"
NAME="$0"
USAGE="This script is a bash script model.
(for this model script only, you can test option with argument on option 't') 

Usage:
    ~\$ sh ${0} -[options [=value]]

Options:
    -h        Help: show this information message
    -v        Verbose: increase script verbosity
    -q        Quiet: decrease script verbosity, nothing will be written unless errors
    -i        Interactive: ask for confirmation before any action
    -x        Debug: see commands to run but not run them actually

You can group options like '-xc', set an option argument like '-d(=)argument'
and use '--' to explicitly specify the end of the script options.
"

#### common settings ##########################

# common options for all scripts
COMMONOPTS=“hiqvx-:”
# bold codes
BOLD='\033[1m'
NC='\033[0m' # No Color

#### library ##########################

#### _echo ( string )
# echo the string with the true 'echo' command
# use this for colorization
_echo () {
    $(which echo) $*
    return 0
}

#### verecho ( string )
# echo the string if "verbose" is "on"
verecho () {
    if test "x$VERBOSE" != 'x'; then echo "$*" >&2; fi;
    return 0
}

#### quietecho ( string )
# echo the string if "quiet" is "off"
quietecho () {
    if test "x$QUIET" = 'x'; then echo "$*" >&2; fi;
    return 0
}

#### iexec ( command , debexec = true )
# execute the command after user confirmation if "interactive" is "on"
iexec () {
    DEBEXECUTION=${2:-true}
    if test "x$INTERACTIVE" != 'x'; then
        prompt "Run command: \"$1\"" "y" "Y/n"
        while true; do
            case $USERRESPONSE in
                [yY]* ) break;;
                * ) echo "_ no" >&2; return 0; break;;
            esac
        done
    fi
    if $DEBEXECUTION; then debexec "$1"; else eval $1; fi
    return 0
}

#### debexec ( command )
# execute the command if "debug" is "off", just write it on screen otherwise
debexec () {
    if test "x$DEBUG" != 'x'; then
        _echo -e "${BOLD}debug >>${NC} $1" >&2
    else
        eval $1
    fi
    return 0
}

#### usage ()
# this function must echo the usage information USAGE (with option "-h")
usage () {
    echo "$USAGE" >&2
    return 0
}

#### title ()
# this function must echo an information about script NAME and VERSION
title () {
    if [ "x$TITLEDONE" = 'x' ]; then
        TITLE="${NAME}"
        if [ "x$VERSION" != 'x' ]; then
            TITLE="${TITLE} - v. [${VERSION}]"
        fi    
        _echo -e "${BOLD}##  ${TITLE}  ##${NC}" >&2
        echo >&2
        export TITLEDONE=true
    fi
    return 0
}

#### info ( string, bold = true )
# writes the string on screen and return
info () {
    USEBOLD=${2:-true}
    if test "$USEBOLD" != 'false'
    then
        _echo -e "${BOLD}   >> $1${NC}" >&2
    else
        _echo -e "${BOLD}   >>${NC} $1" >&2
    fi
    return 0
}


#### prompt ( string , default = y , options = y/n )
# prompt user a string proposing different response options and selecting a default one
# final user fill is loaded in $USERRESPONSE
prompt () {
    local add=""
    if test "x${3}" != 'x'; then add="[${3}] "; fi
    read -p "?  >> ${1} ? ${add}" answer >&2
    export USERRESPONSE=${answer:-$2}
    return 0
}

#### warning ( string )
# writes the error string on screen and return
warning () {
    echo >&2
    _echo -e "${BOLD}!  >> ${1:-unknown error}${NC}" >&2
    echo >&2
    return 0
}

#### error ( string , status = 1 )
# writes the error string on screen and then exit with an error status, default is 1
error () {
    echo >&2
    _echo -e "${BOLD}!! >> ${1:-unknown error}${NC}" >&2
    echo "      (to get help, run: '~$ sh $0 -h')" >&2
    echo >&2
    exit ${2:-1}
}

#### options treatment ##########################

while getopts “t:${COMMONOPTS}” OPTION; do
    OPTARG="${OPTARG#=}"
    case $OPTION in
        h)
            title
            usage
            exit 0
            ;;
        i)
            INTERACTIVE=true
            unset QUIET
            ;;
        v)
            VERBOSE=true
            unset QUIET
            ;;
        x)
            DEBUG=true
            quietecho "  -  debug option enabled: commands shown as 'debug >> cmd' are not executed"
            ;;
        q)
            unset VERBOSE
            unset INTERACTIVE
            QUIET=true
            ;;
        t)
            verecho " - option 't': receiveing argument \"${OPTARG}\""
            ;;
        ?)
            error "Unknown option '$OPTARG'" 1
            ;;
    esac
done

#### process ##########################
quietecho "_ go"

# verecho() usage
verecho "test of verecho() : this must be seen only with option '-v'"

# quietecho() usage
quietecho "test of quietecho() : this must not be written with option '-q'"

# iexec() usage
verecho "test of iexec() : command will be prompted with option '-i'"
iexec "ls -AlGF ."

# info() usage
verecho "test of info() : this will be shown with any option"
info "My test info string"

# warning() usage
verecho "test of warning() : run option '-i' to not throw the error"
iexec "warning 'My test warning info'"

# error() usage
verecho "test of error() : run option '-i' to not throw the error"
iexec "error 'My test error' 3"
echo "this will not be seen if the error has been thrown as the 'error()' function exits the script"

quietecho "_ ok"
#### end ##########################
exit 0

# Endfile
