#!/bin/bash
set -e
set -x

EPV=$1
NOTESTS=$2

if [ "$EPV" == "1" -a "$NOTESTS" == "1" ]
then
	cd phpBB
	composer require phpbb/epv:dev-master --dev --no-interaction
	cd ../
fi