#!/bin/bash

cd $(dirname $0)/..

./vendor/bin/phpstan -l7 analyze src