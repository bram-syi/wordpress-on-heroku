#!/bin/bash

if [ $# != 1 ]; then
    echo "Usage: $0 </path/to/wp-content>"
    exit 1
fi

DIR="$1"
DATE=`date '+%H.%M%p-%m-%d-%y'`

if [ ! -d $DIR ]; then
    echo "$DIR does not exist"
    exit 1
fi

if [ ! -d "$DIR/blogs.dir" ]; then
   echo "No blogs.dir found under $DIR"
   exit 1
fi

cd $DIR || (echo "Unable to 'cd' to $DIR"; exit 1)

nice tar cvzf blogs-$DATE.tgz blogs.dir

echo
echo "blogs-$DATE.tgz contains the blogs in $DIR";
echo



