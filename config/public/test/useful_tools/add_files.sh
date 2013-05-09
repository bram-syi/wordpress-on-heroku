#!/bin/bash
# Add all files in this directory

svn add `svn status --ignore-externals | awk '{ print $2; }'`
