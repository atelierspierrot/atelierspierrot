#/usr/bin/env bash

# list of cleaned files
CLEANUP=( '._*' '.DS_Store' '*~' 'Thumbs.db' 'ehthumbs.db' 'Icon' )

for f in "${CLEANUP[@]}"; do
    rm -rfv "$*" "$f"
done

echo "> OK - directory has been cleaned up"
