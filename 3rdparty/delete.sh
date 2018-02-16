#!/bin/bash
cd "$(dirname "$0")"

echo "############################################################################"
echo "# Remove arc-service-$1 for this device"
echo "############################################################################"
if [ -f /etc/init.d/arc-service-$1 ]; then
    echo "############################################################################"
    echo "# Disconnect android device"
    echo "############################################################################"
    sudo service arc-service-$1 stop
    sudo service arc-service-$1 kill
    sudo update-rc.d arc-service-$1 remove
    sudo systemctl daemon-reload
    sudo rm -Rf /etc/init.d/arc-service-$1
fi
echo "############################################################################"
echo "# Remove arc-service-$1 finnished"
echo "############################################################################"
arc
