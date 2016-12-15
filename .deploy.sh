#!/bin/bash

set -ev

BRANCH=`git name-rev --name-only HEAD`
DATE=`date -u +%Y-%m-%d`

if [[ $BRANCH =~ ^[0-9]{4}\.[0-9]{2}$ ]]; then
	VERSION="`git describe --abbrev=0 --tags --always`-dev"
elif [[ $BRANCH =~ ^tags/[0-9]{4}\.[0-9]{2}\.[0-9]+$ ]]; then
	VERSION="`echo $BRANCH | cut -d'/' -f 2`"
else
	VERSION="`date -u +%y.%m.%d`-dev-$BRANCH"
fi

echo "$BRANCH, $VERSION"

cat .deploy.json | sed "s/###DATE###/$DATE/g" > .deploy.json.new
cat .deploy.json.new | sed "s/###VERSION###/$VERSION/g" > .deploy.json


zip -r ../aimeos_$VERSION.zip * -x .tx -x *.git* -x composer.* -x .deploy.* -x .travis.yml
