#!/bin/bash
set -e
set -x

EXTNAME=$1
BRANCH=$2
EXTPATH_TEMP=$3

# Copy extension to a temp folder
mkdir ../../tmp
cp -R . ../../tmp
cd ../../

# Clone phpBB
git clone --depth=1 "git://github.com/phpbb/phpbb.git" "phpBB3" --branch=$BRANCH