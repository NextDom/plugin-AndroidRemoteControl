#!/bin/bash
touch /tmp/AndroidRemoteControl_dep
if [[ $EUID -ne 0 ]]; then
  sudo_prefix=sudo;
fi
echo 0 > /tmp/AndroidRemoteControl_dep
echo "############################################################################"
echo "# Installation in progress"
echo "############################################################################"
echo "############################################################################"
echo "# Update repository packages and install dependencies"
echo "############################################################################"

echo 5 > /tmp/AndroidRemoteControl_dep
$sudo_prefix apt-get -y update
echo 50 > /tmp/AndroidRemoteControl_dep

$sudo_prefix apt-get -y install android-tools-adb

echo 75 > /tmp/AndroidRemoteControl_dep
$sudoPrefix adb start-server

echo "############################################################################"
echo "# Linking scripts"
echo "############################################################################"
$sudo_prefix ln -s $(dirname "$0")/adbkeepconnection.sh /usr/sbin/adbkeepconnection.sh
$sudo_prefix chmod 777 $(dirname "$0")/adbkeepconnection.sh

echo 90 > /tmp/AndroidRemoteControl_dep

echo "############################################################################"
echo "# Installation Information"
echo "############################################################################"
$sudo_prefix cat /etc/os-release

echo 100 > /tmp/AndroidRemoteControl_dep
rm /tmp/AndroidRemoteControl_dep
echo "############################################################################"
echo "# Installation finnished"
echo "############################################################################"
