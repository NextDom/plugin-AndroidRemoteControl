<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class AndroidRemoteControl extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     */
    public static function cron() {
        foreach (eqLogic::byType('AndroidRemoteControl', true) as $eqLogic) {
            $eqLogic->updateInfo();
        }
    }

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {

    }

    public function postInsert() {

    }

    public function preSave() {

    }

    public function postSave() {
        $cmd = $this->getCmd(null, 'power_state');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('power_state');
            $cmd->setIsVisible(1);
            $cmd->setName(__('État', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('binary');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'ENERGY_STATE');
        $cmd->save();

        $cmd = $this->getCmd(null, 'power_set');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('power_set');
            $cmd->setIsVisible(1);
            $cmd->setName(__('Veille', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'home');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('home');
            $cmd->setIsVisible(1);
            $cmd->setName(__('home', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'play');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('play');
            $cmd->setIsVisible(1);
            $cmd->setName(__('play', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'encours');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('encours');
            $cmd->setIsVisible(1);
            $cmd->setName(__('encours', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'LIGHT_STATE');
        $cmd->save();

        $cmd = $this->getCmd(null, 'name');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('name');
            $cmd->setIsVisible(1);
            $cmd->setName(__('name', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'LIGHT_STATE');
        $cmd->save();

      	$cmd = $this->getCmd(null, 'version');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('version');
            $cmd->setIsVisible(1);
            $cmd->setName(__('version', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'LIGHT_STATE');
        $cmd->save();

        $infos = $this->getInfo();
        $this->updateInfo();
    }

    public function preUpdate() {
        if ($this->getConfiguration('ip') == '') {
            throw new Exception(__('L\'adresse IP doit être renseignée', __FILE__));
        }
    }

    public function postUpdate() {

    }

    public function preRemove() {

    }

    public function postRemove() {

    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclancher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclancher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    public function getInfo() {
        $this->checkAndroidRemoteControlStatus();

        $power_state=substr(shell_exec("sudo adb shell dumpsys power -h | grep \"Display Power\" | cut -c22-"),0 , -1);
        $encours=substr(shell_exec("sudo adb shell dumpsys window windows | grep -E 'mFocusedApp'| cut -d / -f 1 | cut -d \" \" -f 7"), 0, -1);
      	$version=substr(shell_exec("sudo adb shell getprop ro.build.version.release"),0, -1);
        $name=substr(shell_exec("sudo adb shell dumpsys bluetooth_manager -h | grep \"name\" | cut -c9-"), 0, -1);

        return array('power_state' => $power_state, 'encours' => $encours, 'version' => $version, 'name' => $name);
    }

    public function updateInfo() {
        try {
            $infos = $this->getInfo();
        } catch (Exception $e) {
            return;
        }

        if (!is_array($infos)) {
            return;
        }

        if (isset($infos['power_state'])) {
            $this->checkAndUpdateCmd('power_state', ($infos['power_state'] == "ON") ? 1:0 );
        }
      if (isset($infos['encours'])) {
        	switch ($infos['encours']) {
            	case "com.netflix.ninja":
          			$this->checkAndUpdateCmd('encours', "netflix");
        	break;
            	case "tv.molotov.app":
          			$this->checkAndUpdateCmd('encours', "molotov");
          	break;
            case "com.google.android.youtube.tv":
          			$this->checkAndUpdateCmd('encours', "youtube");
          	break;
            case "com.google.android.leanbacklauncher":
          			$this->checkAndUpdateCmd('encours', "acceuil");
          	break;
            case "org.xbmc.kodi":
          			$this->checkAndUpdateCmd('encours', "kodi");
          	break;
            case "com.amazon.amazonvideo.livingroom.nvidia":
          			$this->checkAndUpdateCmd('encours', "amazon");
          	break;
            case "org.videolan.vlc":
          			$this->checkAndUpdateCmd('encours', "vlc");
          	break;
            case "com.vevo":
          			$this->checkAndUpdateCmd('encours', "vevo");
          	break;
            case "com.plexapp.android":
          			$this->checkAndUpdateCmd('encours', "plex");
          	break;
            case "com.spotify.tv.android":
          			$this->checkAndUpdateCmd('encours', "spotify");
          	break;
            default:
                	$this->checkAndUpdateCmd('encours', "inconnu");
          	}
        }
      if (isset($infos['version'])) {
            $this->checkAndUpdateCmd('version', $infos['version']);
        }
      if (isset($infos['name'])) {
            $this->checkAndUpdateCmd('name', $infos['name']);
        }

        throw new Exception(var_dump($infos), 1);
    }

    public function checkAndroidRemoteControlStatus() {
        $check=shell_exec("sudo adb devices | grep ".$this->getConfiguration('ip')." | cut -f2 | xargs");
      	echo $check;
        if(strstr($check, "offline"))
            throw new Exception("Votre appareil est détectée 'offline' par ADB.", 1);
        if(!strstr($check, "device")) {
            #shell_exec("sudo adb kill-server");
            #shell_exec("sudo adb start-server");
            #shell_exec("sudo adb connect ".$eqLogic->getConfiguration('ip'));
            throw new Exception("Votre appareil est non détectée par ADB.", 1);
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}

class AndroidRemoteControlCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        $eqLogic = $this->getEqLogic();

        $eqLogic->checkAndroidRemoteControlStatus();

        if ($this->getLogicalId() == 'power_set') {
            shell_exec("sudo adb shell input keyevent 26");
        } elseif ($this->getLogicalId() == 'home') {
            shell_exec("sudo adb shell input keyevent 3");
        } elseif ($this->getLogicalId() == 'play') {
            shell_exec("sudo adb shell input keyevent KEYCODE_BUTTON_MEDIA_PLAYPAUSE");
        }

        $eqLogic->updateInfo();
    }

    /*     * **********************Getteur Setteur*************************** */
}
