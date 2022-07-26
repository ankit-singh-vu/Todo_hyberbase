#!/bin/bash -x
version_tag=${1:-latest}
../../devsetup/build.sh application registry.origin.triophase.com:5005/hyperbase/application:$version_tag