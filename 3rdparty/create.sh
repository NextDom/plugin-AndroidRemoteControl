#!/bin/bash
cd "$(dirname "$0")"
echo "############################################################################"
echo "# Create/Update arc-service-$1 for this device"
echo "############################################################################"
if [ -f /etc/init.d/arc-service-$1 ]; then
    echo "Service already exist for $1, replace it"
    sudo service arc-service-$1 stop
    sudo update-rc.d -f arc-service-$1 remove
    sudo rm -Rf /etc/init.d/arc-service-$1
fi
sudo cp arc-service /etc/init.d/arc-service-$1

sudo sed -i "s|\@\@name\@\@|$1|g" /etc/init.d/arc-service-$1
sudo sed -i "s|\@\@address\@\@|$2|g" /etc/init.d/arc-service-$1
sudo chmod +x /etc/init.d/arc-service-$1
sudo update-rc.d arc-service-$1 defaults
sudo systemctl daemon-reload

echo "############################################################################"
echo "# Connect android device"
echo "############################################################################"
sudo service arc-service-$1 start

echo "############################################################################"
echo "# Create/Update arc-service-$1 finnished"
echo "############################################################################"
