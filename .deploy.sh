#!/bin/bash

set -ev

BRANCH=`git rev-parse --abbrev-ref HEAD`
TAG=`git describe --abbrev=0 --tags`
DATE=`date -u +%Y-%m-%d`

if test $BRANCH != 'master'; then
	VERSION="$TAG-dev"
else
	VERSION=`date -u +%y.%m.%d-dev`
fi


cat .bintray.json | sed "s/###DATE###/$DATE/" > .bintray.json.new
cat .bintray.json.new | sed "s/###VERSION###/$VERSION/" > .bintray.json


phing -Dversion=$VERSION deploy