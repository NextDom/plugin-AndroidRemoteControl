#!/bin/bash
echo "########### Reset ##########"
echo Arrêt des services en cours :
echo `ls /etc/init.d/arc-service-*`
echo `sudo service arc-service-* stop`
echo `sudo service arc-service-* status`
echo `sudo adb kill-server`
echo `sudo rm /tmp/5037`

echo Pensez à activer à nouveau vos services
echo en sauvegardant les configurations que vous souhaitez relancer parmi :
echo `ls /etc/init.d/arc-service-*`

echo "########### Fin ##########"
