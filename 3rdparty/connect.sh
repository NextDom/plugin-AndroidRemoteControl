#!/bin/bash
if [[ $EUID -ne 0 ]]; then
  sudo_prefix=sudo;
fi
echo "########### connect ##########"
$sudo_prefix adb kill-server
$sudo_prefix adb connect $1;
