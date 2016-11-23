#!/bin/bash

set -ev

DATE=`date -u +%Y-%m-%d`

if test `git rev-parse --abbrev-ref HEAD` != 'master'; then
	VERSION="`git describe --abbrev=0 --tags --always`-dev"
else
	VERSION=`date -u +%y.%m.%d-dev`
fi


cat .deploy.json | sed "s/###DATE###/$DATE/" > .deploy.json.new
cat .deploy.json.new | sed "s/###VERSION###/$VERSION/" > .deploy.json


zip -r ../aimeos_$VERSION.zip * -x .tx -x .git* -x composer.* -x .deploy.* -x .travis.yml
