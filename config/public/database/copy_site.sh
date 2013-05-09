#!/bin/bash

# Copies files and database data from one site to another. The data
# is initially exported to /tmp/var/data, and then imported into the
# $to site.
#
#   ./copy_site.sh live dev2
#
# use environment variables for optional control:
#
#   NO_CHECK=1 will disable the check that is performed
#   to make sure the scripts in $from and $to match.
#
#   NO_EXPORT=1 will skip the export step, which allows you to
#   import into multiple sites after only 1 export.

from=$1
to=$2

[[ "$2" = "live" ]] && { echo "refusing to send anything to 'live' destination"; exit 1; }

cd "$(dirname "$0")"

if ! grep -q "$from.doc_root" config.txt; then
    echo "Source \"$from\" not found"; exit 1; 
fi

if ! grep -q "$to.doc_root" config.txt; then 
    echo "Destination \"$to\" not found"; exit 1;
fi

SRC=`grep "$from.doc_root" config.txt | awk '{print $3}'`
DEST=`grep "$to.doc_root" config.txt | awk '{print $3}'`
echo "Copy from $from(${SRC}) to $to(${DEST})"

mismatch=0
for i in copy_site.sh prepare_load.pl setup_devdb.pl transform_and_import.pl config.txt; do
    if diff "$SRC/database/$i" "$DEST/database/$i" >/dev/null; then
        echo "$i: match"
    else
        echo "$i: different!"
        mismatch=1
    fi
done

if [ -n "$NO_CHECK" ]; then
    echo "disabling script and config mismatch via environment variable"
    mismatch=0
fi

[ $mismatch -eq 1 ] && { echo "scripts and/or config files don't match, aborting"; exit 1; }

check=/tmp/copy_site
if [ -f $check ]; then
    echo "cop_site is already running ($check exists)"
    exit 1
fi
touch $check

if [ -z "$NO_EXPORT" ]; then
    # export
    perl prepare_load.pl --source "$from"
    test $? != 0 && { echo "!! Failed"; exit 1; }
fi

# sync over blogs.dir
rsync -a --progress --delete "$SRC/wp-content/blogs.dir" "$DEST/wp-content"
[ $? -ne 0 ] && { echo "failed to rsync blogs.dir from $SRC to $DEST"; exit 1; }

# import
perl setup_devdb.pl --force --target=$to
test $? != 0 && { echo "!! Failed"; exit 1; }

rm $check
