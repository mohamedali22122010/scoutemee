#!/bin/bash

SCOUTMEE_VERSION="0.1"
BRANCH=$1

# https://forums.aws.amazon.com/thread.jspa?messageID=578531
unset AWS_ACCESS_KEY_ID
unset AWS_SECRET_KEY

# if [ $CIRCLE_BRANCH == 'master' ]
# then
#     AWS_ACCESS_KEY_ID=$(echo $PROD_AWS_ACCESS_KEY_ID)
#     export AWS_ACCESS_KEY_ID
#     AWS_SECRET_KEY=$(echo $PROD_AWS_SECRET_KEY)
#     export AWS_SECRET_KEY
# fi

printf "\n=========== Prepping and Deploying to AWS ===========\n"
# NOW=$(date +"%m-%d-%Y %H:%M:%S")
# echo "Version: $SCOUTMEE_VERSION.$CIRCLE_BUILD_NUM<br>Last elbalad Build: $NOW" > 'public/version.html'
# git config --global user.email 'mohamed.ali@moselaymd.com'
# git config --global user.name 'Mohamed Ali'


# injects branch-environment defaults for elastic beanstalk
# https://forums.aws.amazon.com/thread.jspa?messageID=400420
mkdir -p .elasticbeanstalk
printf "\n=========== elasticbeanstalk Directory has been created ===========\n"

cp config.yml .elasticbeanstalk/

# if [ $CIRCLE_BRANCH == 'master' ]
# then
# 	cp config_prod.yml .elasticbeanstalk/
# fi

printf "\n=========== config file has been copied ===========\n"

eb init
printf "\n=========== eb init ===========\n"

# Uses branch-environment defaults in config.yml
eb deploy
printf "\n=========== eb deploy ===========\n"

# if [ $CIRCLE_BRANCH == 'master' ]
# then
#    git tag "$SCOUTMEE_VERSION.$CIRCLE_BUILD_NUM"
#    git push --tags
# fi
# 