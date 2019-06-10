<?php

/*
 * This file is part of the NextDom software (https://github.com/NextDom or http://nextdom.github.io).
 * Copyright (c) 2018 NextDom.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once "AndroidRemoteControlCmd.class.php";

class AndroidRemoteControl extends eqLogic
{
    public static $_widgetPossibility = array(
        'custom' => true,
        'custom::layout' => false,
        'parameters' => array(
            'sub-background-color' => array(
                'name' => 'Couleur de la barre de contrôle',
                'type' => 'color',
                'default' => 'rgba(0,0,0,0.5)',
                'allow_transparent' => true,
                'allow_displayType' => true,
            ),
        ),
    );

    public static function cron()
    {
        foreach (eqLogic::byType('AndroidRemoteControl', true) as $eqLogic) {
            $eqLogic->updateInfo();
            #$eqLogic->refreshWidget();
        }
    }

    public static function dependancy_info()
    {
        $return                  = array();
        $return['log']           = 'AndroidRemoteControl_dep';
        $return['progress_file'] = '/tmp/AndroidRemoteControl_dep';
        $adb                     = '/usr/bin/adb';
        if (is_file($adb)) {
            $return['state'] = 'ok';
        } else {
            exec('echo AndroidRemoteControl dependency not found : ' . $adb . ' > ' . log::getPathToLog('AndroidRemoteControl_log') . ' 2>&1 &');
            $return['state'] = 'nok';
        }
        return $return;
    }

    public static function dependancy_install()
    {
        log::add('AndroidRemoteControl', 'info', 'Installation des dépéndances android-tools-adb');
        $resource_path = realpath(__DIR__ . '/../../3rdparty');
        passthru('/bin/bash ' . $resource_path . '/install.sh ' . $resource_path . ' > ' . log::getPathToLog('AndroidRemoteControl_dep') . ' 2>&1 &');
    }

  public function runcmd($_cmd)
    {
     $type_connection = $this->getConfiguration('type_connection');
     $ip_address = $this->getConfiguration('ip_address');
     $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
    if ($type_connection == "TCPIP") {
      $data = shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 " . $_cmd);
      return $data;
    }elseif ($type_connection == "TCPIP") {
      $data = shell_exec($sudo_prefix . "adb " . $_cmd);
      return $data;
    }else{
    }
  	}

    public static function resetADB()
    {
        $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
        log::add('AndroidRemoteControl', 'debug', 'Arret du service ADB');
        shell_exec($sudo_prefix . "adb kill-server");
        sleep(3);
        log::add('AndroidRemoteControl', 'debug', 'Lancement du service ADB');
      	shell_exec($sudo_prefix . "adb start-server");

        }

   public function connectADB($_ip_address = null)
    {
        $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
     if (isset($_ip_address)) {
      	$ip_address = $_ip_address;
     }else{
        $ip_address = $this->getConfiguration('ip_address');
     }
      	log::add('AndroidRemoteControl', 'debug', 'Déconnection préventive du périphérique '.$ip_address.' encours');
        shell_exec($sudo_prefix . "adb connect ".$ip_address);
        log::add('AndroidRemoteControl', 'debug', 'Connection au périphérique '.$ip_address.' encours');
        shell_exec($sudo_prefix . "adb connect ".$ip_address);
    }

    public function postSave()
    {
        $data = file_get_contents(__DIR__ . '/../../3rdparty/appli.json');
        $data2 = file_get_contents(__DIR__ . '/../../3rdparty/commandes.json');
        $json= json_encode(array_merge(json_decode($data, true),json_decode($data2, true)));
        $json_a = json_decode($json);
        foreach ($json_a as $json_cmd) {
            $cmd = $this->getCmd(null, $json_cmd->name);
            if (!is_object($cmd)) {
                $cmd = new AndroidRemoteControlCmd();
                $cmd->setLogicalId($json_cmd->name);
                $cmd->setName(__($json_cmd->name, __FILE__));
            }
            $cmd->setType($json_cmd->type);
            $cmd->setSubType($json_cmd->subtype);
            $cmd->setConfiguration('categorie', $json_cmd->categorie);
            $cmd->setConfiguration('icon', $json_cmd->icon);
            $cmd->setConfiguration('commande', $json_cmd->commande);
            $cmd->setEqLogic_id($this->getId());
            $cmd->save();
        }

        $volume = $this->getCmd(null, 'volume');
        if (!is_object($volume)) {
            $volume = new AndroidRemoteControlCmd();
            $volume->setLogicalId('volume');
            $volume->setName(__('Volume', __FILE__));
        }
        $volume->setUnite('%');
        $volume->setType('info');
        $volume->setSubType('numeric');
        $volume->setConfiguration('categorie', "commande");
        $volume->setEqLogic_id($this->getId());
        $volume->save();

        $cmd = $this->getCmd(null, 'setVolume');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('setVolume');
            $cmd->setName(__('setVolume', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('slider');
        $cmd->setConfiguration('categorie', "commande");
        $cmd->setValue($volume->getId());
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

      $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
         if ($this->getConfiguration('type_connection') == "TCPIP") {
        log::add('AndroidRemoteControl', 'debug', "Restart ADB en mode TCP");
        $check = shell_exec($sudo_prefix . "adb devices TCPIP 5555");
      } elseif ($this->getConfiguration('type_connection') == "SSH") {
       log::add('AndroidRemoteControl', 'debug', "Check de la connection SSH");
      } else{
      log::add('AndroidRemoteControl', 'debug', "Restart ADB en mode USB");
        $check = shell_exec($sudo_prefix . "adb devices USB");
      }
    }

    public function preUpdate()
    {
        if ($this->getConfiguration('ip_address') == '') {
            throw new \Exception(__('L\'adresse IP doit être renseignée', __FILE__));
        }
    }

    public function getInfo()
    {
        $this->checkAndroidRemoteControlStatus();
        $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
        $ip_address = $this->getConfiguration('ip_address');

        $power_state = substr($this->runcmd("shell dumpsys power -h | grep \"Display Power\" | cut -c22-"), 0, -1);
       	log::add('AndroidRemoteControl', 'debug', "power_state: " . $power_state);
        $encours     = substr($this->runcmd("shell dumpsys window windows | grep -E 'mFocusedApp'| cut -d / -f 1 | cut -d \" \" -f 7"), 0, -1);
      log::add('AndroidRemoteControl', 'debug', "encours: " .$encours );
        $version_android     = substr($this->runcmd("shell getprop ro.build.version.release"), 0, -1);
      log::add('AndroidRemoteControl', 'debug', "version_android: " .$version_android );
        $name        = substr($this->runcmd("shell getprop ro.product.model"), 0, -1);
      log::add('AndroidRemoteControl', 'debug', "name: " .$name );
        $type        = substr($this->runcmd("shell getprop ro.build.characteristics"), 0, -1);
      log::add('AndroidRemoteControl', 'debug', "type: " .$type);
        $resolution  = substr($this->runcmd("shell dumpsys window displays | grep init | cut -c45-53"), 0, -1);
      log::add('AndroidRemoteControl', 'debug', "resolution: " .$resolution );
        $disk_free = substr($this->runcmd("shell dumpsys diskstats | grep Data-Free | cut -d' ' -f7"), 0, -1);
        log::add('AndroidRemoteControl', 'debug', "disk_free: " .$disk_free );
        $disk_total = round(substr($this->runcmd("shell dumpsys diskstats | grep Data-Free | cut -d' ' -f4"), 0, -1)/1000000, 1);
        log::add('AndroidRemoteControl', 'debug', "disk_total: " .$disk_total);
        $title = substr($this->runcmd("shell dumpsys bluetooth_manager | grep MediaPlayerInfo | grep .$encours. |cut -d')' -f3 | cut -d, -f1 | grep -v null | sed 's/^\ *//g'"), 0);        log::add('AndroidRemoteControl', 'debug', "title: " .$title);
        $volume = substr($this->runcmd("shell media volume --stream 3 --get | grep volume |grep is | cut -d\ -f4"), 0, -1);
      log::add('AndroidRemoteControl', 'debug', "volume: " .$volume);
        $play_state  = substr($this->runcmd("shell dumpsys bluetooth_manager | grep mCurrentPlayState | cut -d,  -f1 | cut -c43-"), 0, -1);
      log::add('AndroidRemoteControl', 'debug',  "play_state: " .$play_state );
        $battery_level  = substr($this->runcmd("shell dumpsys battery | grep level | cut -d: -f2"), 0, -1);
      log::add('AndroidRemoteControl', 'debug', "battery_level: " .$battery_level);
        $battery_status  = substr($this->runcmd("shell dumpsys battery | grep status"), -3);
      log::add('AndroidRemoteControl', 'debug', "battery_status: " .$battery_status);

        return array('power_state' => $power_state, 'encours' => $encours, 'version_android' => $version_android, 'name' => $name, 'type' => $type, 'resolution' => $resolution, 'disk_total' => $disk_total, 'disk_free' => $disk_free, 'title' => $title, 'volume' => $volume, 'play_state' => $play_state, 'battery_level' => $battery_level, 'battery_status' => $battery_status);
    }

    public function updateInfo()
    {
        try {
            $infos = $this->getInfo();
        } catch (\Exception $e) {
            return;
        }

        if (!is_array($infos)) {
            return;
        }
        log::add('AndroidRemoteControl', 'info', 'Rafraichissement des informations');
        if (isset($infos['power_state'])) {
            $this->checkAndUpdateCmd('power_state', ($infos['power_state'] == "ON") ? 1 : 0 );
        }
        if (isset($infos['encours'])) {
            $cmd = $this->getCmd(null, 'encours');
            $url = __DIR__ . '/../../3rdparty/appli.json';
            $data = file_get_contents($url);
            $json_a = json_decode($data);
            foreach ($json_a as $json_b) {
                if (stristr($infos['encours'], $json_b->name)){
                    $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/'.$json_b->icon);
                    $this->checkAndUpdateCmd('encours', $json_b->name);
                }
            }
            $cmd->save();
        }
        if (isset($infos['version_android'])) {
            $this->checkAndUpdateCmd('version_android', $infos['version_android']);
        }
        if (isset($infos['name'])) {
            $this->checkAndUpdateCmd('name', $infos['name']);
        }

        if (isset($infos['type'])) {
            $this->checkAndUpdateCmd('type', $infos['type']);
        }
        if (isset($infos['resolution'])) {
            $this->checkAndUpdateCmd('resolution', $infos['resolution']);
        }
        if (isset($infos['disk_free'])) {
            $this->checkAndUpdateCmd('disk_free', $infos['disk_free']);
        }
        if (isset($infos['disk_total'])) {
            $this->checkAndUpdateCmd('disk_total', $infos['disk_total']);
        }
        if (isset($infos['title'])) {
            $this->checkAndUpdateCmd('title', $infos['title']);
        }
        if (isset($infos['volume'])) {
            $this->checkAndUpdateCmd('volume', $infos['volume']);
        }
        if (isset($infos['play_state'])) {
            if ($infos['play_state'] == 2) {
                $this->checkAndUpdateCmd('play_state', "pause");
            }elseif ($infos['play_state'] == 3){
                $this->checkAndUpdateCmd('play_state', "lecture");
            }elseif ($infos['play_state'] == 0){
                $this->checkAndUpdateCmd('play_state', "arret");
            }else
            $this->checkAndUpdateCmd('play_state',"inconnue");
        }

        if (isset($infos['battery_level'])) {
            $this->checkAndUpdateCmd('battery_level', $infos['battery_level']);
        }
        if (isset($infos['battery_status'])) {
            if ($infos['battery_status'] == 2) {
                $this->checkAndUpdateCmd('battery_status',"en charge");
            } elseif ($infos['battery_status'] == 3) {
                $this->checkAndUpdateCmd('battery_status',"en décharge");
            } elseif ($infos['battery_status'] == 4) {
                $this->checkAndUpdateCmd('battery_status',"pas de charge");
            } elseif ($infos['battery_status'] == 5 ) {
                $this->checkAndUpdateCmd('battery_status',"pleine");
            } else
            $this->checkAndUpdateCmd('battery_status',"inconnue");
        }
    }

    public function checkAndroidRemoteControlStatus()
    {
        $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
        $ip_address = $this->getConfiguration('ip_address');

      if ($this->getConfiguration('type_connection') == "TCPIP") {
        log::add('AndroidRemoteControl', 'debug', "Check de la connection TCPIP");
        $check = shell_exec($sudo_prefix . "adb devices | grep " . $ip_address . " | cut -f2 | xargs");
      } elseif ($this->getConfiguration('type_connection') == "SSH") {
       log::add('AndroidRemoteControl', 'debug', "Check de la connection SSH");
      } else{
      log::add('AndroidRemoteControl', 'debug', "Check de la connection USB");
        $check = shell_exec($sudo_prefix . "adb devices | grep " . $ip_address . " | cut -f2 | xargs");
      }
              if (strstr($check, "offline")) {
            $cmd = $this->getCmd(null, 'encours');
            log::add('AndroidRemoteControl', 'info', 'Votre appareil est offline');
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/erreur.png');
            $cmd->save();
            $this->connectADB($ip_address);
        } elseif (!strstr($check, "device")) {
            $cmd = $this->getCmd(null, 'encours');
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/erreur.png');
            $cmd->save();
            log::add('AndroidRemoteControl', 'info', 'Votre appareil n\'est pas détecté par ADB.');
            $this->connectADB($ip_address);
        } elseif (strstr($check, "unauthorized")) {
            $cmd = $this->getCmd(null, 'encours');
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/erreur.png');
            $cmd->save();
            log::add('AndroidRemoteControl', 'info', 'Votre connection n\'est pas autorisé');
            $this->connectADB($ip_address);
        }
    }

    public function toHtml($_version = 'dashboard') {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $version = jeedom::versionAlias($_version);
        $replace['#version#'] = $_version;
        if ($this->getDisplay('hideOn' . $version) == 1) {
            return '';
        }

        foreach ($this->getCmd('info') as $cmd) {
            $replace['#' . $cmd->getLogicalId() . '_history#'] = '';
            $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
            $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
            $replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();

            if ($cmd->getLogicalId() == 'encours'){
                $replace['#thumbnail#'] = $cmd->getDisplay('icon');
            }

            if ($cmd->getLogicalId() == 'play_state'){
                if($cmd->execCmd() == 'play'){
                    $replace['#play_pause#'] = '"fa fa-pause  fa-lg" style="color:green"';
                }else{
                    $replace['#play_pause#'] = '"fa fa-play  fa-lg"';
                }
            }

            if ($cmd->getIsHistorized() == 1) {
                $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
            }
            $replace['#' . $cmd->getLogicalId() . '_id_display#'] = ($cmd->getIsVisible()) ? '#' . $cmd->getLogicalId() . "_id_display#" : "none";
        }
        foreach ($this->getCmd('action') as $cmd) {
            if ($cmd->getConfiguration('categorie') == 'appli'){
                $replace['#applis#'] = $replace['#applis#'] . '<a class="btn cmd icons noRefresh" style="display:#'.$cmd->getLogicalId().'_id_display#; padding:3px" data-cmd_id="'.$cmd->getId().'" title="'.$cmd->getName().'" onclick="jeedom.cmd.execute({id: '.$cmd->getId().'});"><img src="plugins/AndroidRemoteControl/desktop/images/'.$cmd->getConfiguration('icon') .'"></a>';
            }else{
                $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
                $replace['#' . $cmd->getLogicalId() . '_id_display#'] = (is_object($cmd) && $cmd->getIsVisible()) ? '#' . $cmd->getId() . "_id_display#" : 'none';
            }
            $replace['#' . $cmd->getLogicalId() . '_id_display#'] = ($cmd->getIsVisible()) ? '#' . $cmd->getLogicalId() . "_id_display#" : "none";
        }

        $replace['#ip#'] = $this->getConfiguration('ip_address');

        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'eqLogic', 'AndroidRemoteControl')));
    }

}
