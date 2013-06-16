#!/bin/bash
#
# Copyleft (c) 2013 Pierre Cassat and contributors
# <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
# License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
# Sources <http://github.com/atelierspierrot/atelierspierrot>
# 
# console/bookmarker.sh
#
# For more infos run:
# 
#     ~$ sh path/to/bookmarker.sh -h
# 
# Or see: <http://github.com/atelierspierrot/atelierspierrot/Shell-Scripts.md>
# 

#### HOW TO ##############################################################################
#
# To build a new shell script based on this model, make a copy of it, rename it as 
# `XXX.sh`, give it execution rights running `chmod +x XXX.sh` and then open it in an
# editor like VIM.
#
# The first thing to do is to edit the "script settings" part below writing your script name 
# and version and a long string explaining its usage. You can add any setting required in 
# your script.
#
# Then go to the "script options" section to add your options and write their treatments.
#
# Finally, go to the "script process" section and write your script.
#

#### SCRIPT SETTINGS #####################################################################

VERSION="0.0.1-dev"
NAME="$0"
USAGE="Create a bookmark file to remind a web-address. \n\
\n\
Usage:\n\
    ~\$ ${0} -[options [=value]] <URL>\n\
\n\
Options:\n\
    -n=X      Name: name of the generated file (default is URL's domain name)\n\
    -t=X      Timeout: defines the timeout before redirection (default is 0)\n\
    -c=\"X\"    Comment: add a comment string to the generated HTML file \n\
    -h        Help: show this information message\n\
    -v        Verbose: increase script verbosity\n\
    -q        Quiet: decrease script verbosity, nothing will be written unless errors\n\
    -i        Interactive: ask for confirmation before any action\n\
    -x        Debug: see commands to run but not run them actually\n\
\n\
You can group options like '-xc', set an option argument like '-d(=)argument'\n\
and use '--' to explicitly specify the end of the script options.\n\
";

#### LIBRARY #############################################################################

# common options for all scripts
COMMONOPTS="hiqvxt:c:-:"

# text formating codes
BOLD='\033[1m'
ULINE='\033[4m'
NC='\033[0m' # No Color

# user OS
_USEROS="$(uname)"

#### _echo ( string )
# echo the string with the true 'echo' command
# use this for colorization
_echo () {
    tput sgr0
    case $_USEROS in
        Linux|FreeBSD|OpenBSD|SunOS) $(which echo) -e $*;;
        *) echo $*;;
    esac
    return 0
}

#### verecho ( string )
# echo the string if "verbose" is "on"
verecho () {
    if test "x$VERBOSE" != 'x'; then echo "$*"; fi; return 0;
}

#### quietecho ( string )
# echo the string if "quiet" is "off"
quietecho () {
    if test "x$QUIET" = 'x'; then echo "$*"; fi; return 0;
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
                * ) echo "_ no"; return 0; break;;
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
        _echo "${BOLD}debug >>${NC} $1"
    else
        eval $1
    fi
    return 0
}

#### usage ()
# this function must echo the usage information USAGE (with option "-h")
usage () {
    echo "$USAGE"; return 0;
}

#### title ()
# this function must echo an information about script NAME and VERSION
title () {
    if [ "x$TITLEDONE" = 'x' ]; then
        TITLE="${NAME}"
        if [ "x$VERSION" != 'x' ]; then TITLE="${TITLE} - v. [${VERSION}]"; fi    
        _echo "${BOLD}##  ${TITLE}  ##${NC}"
        echo
        export TITLEDONE=true
    fi
    return 0
}

#### info ( string, bold = true )
# writes the string on screen and return
info () {
    USEBOLD=${2:-true}
    if test "$USEBOLD" != 'false'; then
        _echo "${BOLD}   >> $1${NC}"
    else
        _echo "${BOLD}   >>${NC} $1"
    fi
    return 0
}

#### prompt ( string , default = y , options = Y/n )
# prompt user a string proposing different response options and selecting a default one
# final user fill is loaded in $USERRESPONSE
prompt () {
    local add=""
    if test "x${3}" != 'x'; then add="[${3}] "; fi
    read -p "?  >> ${1} ? ${add}" answer
    export USERRESPONSE=${answer:-$2}
    return 0
}

#### warning ( string )
# writes the error string on screen and return
warning () {
    echo
    _echo "${BOLD}!  >> ${1:-unknown error}${NC}"
    echo
    return 0
}

#### error ( string , status = 1 )
# writes the error string on screen and then exit with an error status, default is 1
error () {
    echo
    _echo "${BOLD}!! >> ${1:-unknown error}${NC}"
    echo "      (to get help, run: '~$ sh $0 -h')"
    echo
    exit ${2:-1}
}

#### SCRIPT OPTIONS ######################################################################

BK_URL="${@: -1}"
while getopts "${COMMONOPTS}" OPTION; do
    OPTARG="${OPTARG#=}"
    case $OPTION in
    # you must keep these ones
        h) title; usage; exit 0;;
        i) INTERACTIVE=true; unset QUIET;;
        v) VERBOSE=true; unset QUIET;;
        x) DEBUG=true;
            quietecho "  -  debug option enabled: commands shown as 'debug >> cmd' are not executed";;
        q) unset VERBOSE; unset INTERACTIVE; QUIET=true;;
        t) BK_TIMEOUT=$OPTARG;;
        n) BK_NAME=$OPTARG;;
        c) BK_COMMENT="$OPTARG";;
        ?) error "Unknown option '$OPTARG'" 1;;
    esac
done

#### SCRIPT PROCESS ######################################################################

if [ -z $BK_URL ]; then
    error "No URL specified (use option '-h' to get help) !"
fi
if [ -z $BK_NAME ]; then
    BK_NAME=$(echo "$BK_URL" | awk -F/ '{print $3}')
fi
if [ -z $BK_TIMEOUT ]; then
    BK_TIMEOUT=0
fi

cat <<EOF > "${BK_NAME}.html"
<!DOCTYPE html>
<html><head>
    <meta charset="UTF-8" />
    <title>${BK_NAME}</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta http-equiv="refresh" content="${BK_TIMEOUT}; ${BK_URL}" />
<style type="text/css">
body, ul, li {margin:0; padding:0;font-family: Helvetica}
body {background-color:#C5CCD4;background: -moz-repeating-linear-gradient( 0, #C5CCD4 0%, #C5CCD4 29%, #CBD2D8 29%, #CBD2D8 100% );background-image: -webkit-gradient(linear, left top, right top,  color-stop(0.7142, #C5CCD4), color-stop(0.7142, #CBD2D8));-webkit-background-size:7px 1px;-moz-background-size:7px 7px;background-size:7px 1px;background-repeat:repeat}
h1 {color: white;font-family: Helvetica;font-size: 20px;font-weight: bold;margin: auto;overflow: hidden;text-align: center;text-overflow: ellipsis;text-shadow: rgba(0, 0, 0, 0.4) 0px -1px 0px;white-space: nowrap;width: 150px}
header {background-image: -webkit-gradient(linear, 0% 0%, 0% 50%, from(rgba(176, 188, 205, 1)), to(rgba(129, 149, 175, 1)));background-image: -moz-linear-gradient(top, rgb(176, 188, 205) 50%, rgb(129, 149, 175) 100%);padding: 7px 10px;background-color: rgb(109, 132, 162);border-bottom-color:1px solid rgb(45, 54, 66);border-top:1px solid rgb(109, 132, 162);display: block;height: 31px;line-height: 30px;display: block;border-bottom: 1px solid #2C3542;border-top: 1px solid #CDD5DF}
.button {color: #FFFFFF;text-decoration: none;background-image: -webkit-gradient(linear, top, bottom, from(#C5CCD4), to(#fff));background-image: -moz-linear-gradient(top, #C5CCD4, rgba(255,255,255,0.2));text-shadow:  0 -1px 0 rgba(0, 0, 0, 0.6);overflow: hidden;max-width: 80px;white-space: nowrap;text-overflow: ellipsis;font-family: Helvetica; font-size: 12px;font-weight:bold;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;height:30px;padding: 0 10px;-webkit-background-size: 100% 50%;-moz-background-size: 100% 50%;background-size: 100% 50%;background-repeat:no-repeat;border: 1px solid #2F353E;-webkit-box-shadow: 0 1px 0 rgba(255,255,255, 0.4), inset 0 1px 0 rgba(255,255,255,0.4);-moz-box-shadow: 0 1px 0 rgba(255,255,255, 0.4), inset 0 1px 0 rgba(255,255,255,0.4);box-shadow: 0 1px 0 rgba(255,255,255, 0.4), inset 0 1px 0 rgba(255,255,255,0.4);}
.nav li {padding: 8px;}
.cancel {float:left;background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#8EA4C1), to(#5877A2));background-color:#4A6C9B;border-color: #2F353E #375073 #375073}
.done {float: right;background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#7B9EEA), to(#376FE0));background-color: #2463DE}
article ul{width: 300px;background-color: #ffffff;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;border: 1px solid #A8ABAE;display: block;margin: 10px auto}
article li {list-style-type:none;line-height: 44px;border-bottom:1px solid #A8ABAE;padding: 0 10px;text-align:center}
article ul li:last-of-type {border-bottom:none}
a{text-decoration:none}
</style>
<script type="text/javascript">addEventListener("load", function() {setTimeout(hideURLbar, 0);}, false);function hideURLbar(){window.scrollTo(0,1);}</script>
</head><body>
<header><nav><h1>${BK_NAME} - Bookmarker redirect</h1></nav></header>
<article><ul><li><a href="${BK_URL}">${BK_URL}</a></li></ul><p>${BK_COMMENT}</p></article>
</body></html>
EOF
info "OK - boolmarker created in '${BK_NAME}.html'"

#### END OF SCRIPT #######################################################################
exit 0

# Endfile
