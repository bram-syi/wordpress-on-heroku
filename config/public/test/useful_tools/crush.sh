#!/bin/sh
# Crush all images in this directory

for png in `find $1 -name "*.png"`;
do
  echo "crushing $png"  
  pngcrush -brute "$png" temp.png
  mv -f temp.png $png
done;
jpegoptim --strip-all *.jpg
