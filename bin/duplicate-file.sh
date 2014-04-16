#!/bin/bash

declare -x VERBOSE=false
declare -x FORCE=false
declare -x SUFFIX='copy'

usage () {
    echo "usage:   $0  [-h|-f|-v]  [-s=suffix]  <file-path>  [<target-name>]"
}

help () {
    echo
    echo "## File duplicator ##"
    echo "This script creates a copy of an input file or directory in the same directory, naming it 'ORIGINAL_NAME.copy.EXT'."
    echo
    usage
    echo
    echo "        <file-path>       : absolute path of the file to copy"
    echo "        <target-path>     : absolute path or file name of the copy"
    echo "                            default is 'ORIGINAL_NAME.copy(.EXT)' in the original directory"
    echo "        -s =STRING        : replace the default 'copy' suffix"
    echo "        -h                : get help"
    echo "        -v                : increase script's verbosity"
    echo "        -f                : force over-writing any existing copy"
    echo "####"
    echo
}

error () {
    echo "$1"
    echo
    usage
    echo "(use option '-h' for help)"
    echo
    exit ${2:-1}
}

OPTIND=1
while getopts "hvfs:" OPTION; do
    case $OPTION in
        h) help && exit 0;;
        v) VERBOSE=true;;
        f) FORCE=true;;
        s) SUFFIX="${OPTARG#=}";;
        \?) error "unknown option '${OPTARG}'!";;
        :) error "option '${OPTARG}' requires an argument!";;
    esac
done
set -- "${@:OPTIND}"

if [ $# -eq 0 ]; then error "You must precise the path to work on !"; fi

SOURCEFILE=$1
if [ ! -e ${SOURCEFILE} ]; then error "Path '${SOURCEFILE}' can not be found !"; fi

TARGET_MASK_FILE="%s.${SUFFIX}.%s"
TARGET_MASK_DIR="%s.${SUFFIX}"
if [ ! -z $2 ]; then
    TARGET_MASK_FILE="$2"
    TARGET_MASK_DIR="$2"
fi

CP_CMD=$(which cp)
CP_ARGS=""

SOURCEFILE_FILENAME=$(basename "${SOURCEFILE}")
if [ -f ${SOURCEFILE} ]
then
    SOURCEFILE_EXTENSION="${SOURCEFILE_FILENAME##*.}"
    SOURCEFILE_NAME="${SOURCEFILE_FILENAME%.*}"
    TARGET_FILENAME=$(printf "${TARGET_MASK_FILE}" "${SOURCEFILE_NAME}" "${SOURCEFILE_EXTENSION}")
    VERBOSE_INFO="> copying file '${SOURCEFILE}' to '${TARGET_FILENAME}'";
else
    SOURCEFILE_NAME="${SOURCEFILE_FILENAME}"
    TARGET_FILENAME=$(printf "${TARGET_MASK_DIR}" "${SOURCEFILE_NAME}")
    CP_ARGS+=" -r"
    VERBOSE_INFO="> copying directory '${SOURCEFILE}' to '${TARGET_FILENAME}'";
fi

if ${FORCE}; then CP_ARGS+=" -f"; fi
if ${VERBOSE}; then echo "${VERBOSE_INFO}"; fi

_ok=$(eval ${CP_CMD} "${CP_ARGS}" "${SOURCEFILE}" "${TARGET_FILENAME}");
if ${ok}
then echo "${TARGET_FILENAME}";
else echo "error while copying '${SOURCEFILE}' to '${TARGET_FILENAME}'";
fi

exit 0
# Endfile