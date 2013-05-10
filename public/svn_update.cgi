#!/bin/sh
set -f
echo "Content-type: text/plain; charset=iso-8859-1"
echo
date
if [ "${QUERY_STRING}" = "revert" ]
then
  /usr/bin/svn revert -R . 2>&1
fi
/usr/bin/svn update --ignore-externals 2>&1
echo
/usr/bin/svn status --ignore-externals -u
echo
/usr/bin/wget -O - ${SERVER_NAME}/database/db-patches.php
