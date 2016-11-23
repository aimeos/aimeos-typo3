#!/bin/bash

set -ev

DATE=`date -u +%Y-%m-%d`

if test `git rev-parse --abbrev-ref HEAD` != 'master'; then
	VERSION="`git describe --abbrev=0 --tags`-dev"
else
	VERSION=`date -u +%y.%m.%d-dev`
fi


cat .deploy.json | sed "s/###DATE###/$DATE/" > .deploy.json.new
cat .deploy.json.new | sed "s/###VERSION###/$VERSION/" > .deploy.json


phing -Dversion=$VERSION deploy