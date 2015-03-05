#!/usr/bin/env bash
#
# Usage:
#
# - with no argument, the whole changelog will be displayed
# - with a TAG1..TAG2, the diff changelog only will be displayed
#
#       git-changelog.sh all
#       git-changelog.sh hash
#       git-changelog.sh TAG1..TAG2
#       git-changelog.sh ... > CHANGELOG
#

declare CHANGELOG_TITLE='# CHANGELOG for remote repository %s'
declare HEAD_HEADER='* (upcoming release)'
declare TAG_HEADER='* %(tag) (%(taggerdate:short) - %%s)'
declare COMMIT_LOG='    * %h - %s (%cN)'

usage () {
    cat <<MESSAGE
usage:          $0 <arg>
with <arg> in:
                'all'        : get the full repository changelog
                'tag1..tag2' : get a changelog between tag1 and tag2 (tag1 < tag2)
                'hash'       : get a single commit changelog message
                'init'       : get the full repository initial changelog (when no tag is available yet)
MESSAGE
}

# repo_remote
repo_remote () {
    echo "$(git remote show -n origin | grep 'Fetch' | cut -d':' -f 2-)"
}

# tag_title TAG_REF
tag_title () {
    local TAGREF=$(tag_header "${1}")
    local tmp=$(git --no-pager for-each-ref --sort='-taggerdate' --format="$TAG_HEADER" 'refs/tags' | grep " ${1} ")
    printf "$tmp" "$TAGREF"
}

# tag_header TAG_REF
tag_header () {
    git --no-pager show-ref --hash --abbrev "${1}"
}

# tag_history TAG1 TAG2
tag_history () {
    if [ $# -eq 2 ]; then
        git --no-pager log --oneline --first-parent --no-merges --decorate --pretty=tformat:"$COMMIT_LOG" "${1}..${2}"
    elif [ $# -eq 1 ]; then
        git --no-pager log --oneline --first-parent --no-merges --decorate --pretty=tformat:"$COMMIT_LOG" "${1}"
    fi
}

# commit_history HASH
commit_history () {
    git --no-pager log --oneline --first-parent --no-merges --decorate --pretty=tformat:"$COMMIT_LOG" -1 "${1}"
}

# get_history
get_history () {
    git --no-pager log --oneline --all --decorate --pretty=tformat:"$COMMIT_LOG"
}

# get_changelog TAG1 TAG2
get_changelog () {
    if [ $# -eq 2 ]; then
        local TAG1="${1}"
        local TAG2="${2}"
    elif [ $# -eq 1 ]; then
        local TAG2="${1}"
        local TAG1=''
    else
        return 1
    fi
    if [ "$TAG2" = 'HEAD' ]; then
        echo "$HEAD_HEADER"
    else
        echo "$(tag_title "$TAG2")"
    fi
    echo
    if [ -n "$TAG1" ]; then
        tag_history "$TAG1" "$TAG2"
    else
        tag_history "$TAG2"
    fi
    echo
}

# arguments ?
if [ $# -eq 0 ]; then
    usage
    exit 1
fi
ARGS="${*}"

# get the whole repo history
if [ "$ARGS" = 'full' ]||[ "$ARGS" = 'all' ]; then
    REPO=$(repo_remote)
    echo "$(printf "$CHANGELOG_TITLE" "$REPO")"
    echo
    TAG1=''
    TAG2='HEAD'
    all_tags="$(git for-each-ref --sort='-taggerdate' --format='%(refname)' 'refs/tags')"
    COUNTER=1
    TAGSCOUNTER=$(echo "$all_tags" | wc -l )
    echo "$all_tags" | while read tag; do
        TAG1="${tag//refs\/tags\//}"
        if [ -n "$TAG2" ]; then
            get_changelog "$TAG1" "$TAG2"
        else
            get_changelog "$TAG1"
        fi
        TAG2="${tag//refs\/tags\//}"
        COUNTER=$((COUNTER+1))
        if [ "$COUNTER" -eq $((TAGSCOUNTER+1)) ]; then
            get_changelog "$TAG2"
        fi
    done

# get initial changelog
elif [ "$ARGS" = 'init' ]; then
    REPO=$(repo_remote)
    echo "$(printf "$CHANGELOG_TITLE" "$REPO")"
    echo
    get_history

else

    tag=$(echo "$ARGS" | grep '\.\.')
    # get the history between two tags
    if [ -n "$tag" ]; then
        tmpargs=$(echo "$ARGS" | sed -e 's/\.\./;/g')
        TAG1=$(echo "$tmpargs" | cut -d';' -f 1)
        TAG2=$(echo "$tmpargs" | cut -d';' -f 2)
        get_changelog "$TAG1" "$TAG2"

    else

        commit=$(git branch -a --contains="$ARGS" &>/dev/null; echo $?)
        # get the history of a single commit
        if [ "$commit" = 0 ]; then
            commit_history "$ARGS"

        else
            # else error, args not understood
            usage
            exit 1
        fi
    fi
fi
