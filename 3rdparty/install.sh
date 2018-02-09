#!/bin/bash
touch /tmp/AndroidRemoteControl_dep
echo 0 > /tmp/AndroidRemoteControl_dep
echo "############################################################################"
echo "# Installation in progress"
echo "############################################################################"
echo "############################################################################"
echo "# Update repository packages and install dependencies"
echo "############################################################################"

echo 5 > /tmp/AndroidRemoteControl_dep
sudo apt-get -y update
echo 50 > /tmp/AndroidRemoteControl_dep

sudo apt-get -y install android-tools-adb

echo 90 > /tmp/AndroidRemoteControl_dep

echo "############################################################################"
echo "# Installation Information"
echo "############################################################################"
sudo cat /etc/os-release

echo 100 > /tmp/AndroidRemoteControl_dep
rm /tmp/AndroidRemoteControl_dep
echo "############################################################################"
echo "# Installation finnished"
echo "############################################################################"
