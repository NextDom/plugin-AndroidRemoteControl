#!/bin/bash

echo "########### Suppression en cours ##########"
echo "############################################################################"
echo "# Removing all arc-service"
echo "############################################################################"
sudo service arc-service-* stop
sudo update-rc.d arc-service-* remove
sudo systemctl daemon-reload


echo "############################################################################"
echo "# Remove packages and dependencies"
echo "############################################################################"
sudo apt-get -y remove android-tools-adb

echo "############################################################################"
echo "# Remove all plugin configuration files"
echo "############################################################################"
sudo rm -Rf /etc/init.d/arc-service-*

sudo rm -Rf /tmp/AndroidRemoteControl_dep

echo "########### Fin ##########"
