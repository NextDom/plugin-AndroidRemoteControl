#!/bin/bash

if [[ $EUID -ne 0 ]]; then
  sudo_prefix=sudo;
fi
echo "########### Etat du service $1 ##########"
echo `$sudo_prefix service arc-service-$1 status`
echo "########### Fin ##########"
