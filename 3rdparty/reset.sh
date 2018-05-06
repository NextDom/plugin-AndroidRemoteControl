#!/bin/bash
if [[ $EUID -ne 0 ]]; then
  sudo_prefix=sudo;
fi
echo "########### Reset ##########"
echo Arrêt des services en cours :
echo `$sudo_prefix adb kill-server`

echo `$sudo_prefix killall adb`

echo Redémarrage des services :
echo `$sudo_prefix adb start-server`
echo "########### Fin ##########"
