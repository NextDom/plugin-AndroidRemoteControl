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
    public static function dependancy_info() {
      $return = array();
      $return['log'] = 'AndroidRemoteControl_dep';
      $return['progress_file'] = '/tmp/AndroidRemoteControl_dep';
      $adb = '/usr/bin/adb';
      if (is_file($adb)) {
        $return['state'] = 'ok';
      } else {
  			exec('echo AndroidRemoteControl dependency not found : '. $adb . ' > ' . log::getPathToLog('AndroidRemoteControl_log') . ' 2>&1 &');
        $return['state'] = 'nok';
      }
      return $return;
    }

    public static function dependancy_install() {
      log::add('AndroidRemoteControl','info','Installation des dépéndances android-tools-adb');
      $resource_path = realpath(dirname(__FILE__) . '/../../3rdparty');
      passthru('/bin/bash ' . $resource_path . '/install.sh ' . $resource_path . ' > ' . log::getPathToLog('AndroidRemoteControl_dep') . ' 2>&1 &');
    }
  	/*     * ***********************Methode static*************************** */
  	public static function updateAndroidRemoteControl() {
  		log::remove('AndroidRemoteControl_update');
  		$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../3rdparty/install.sh';
  		$cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_update') . ' 2>&1 &';
  		exec($cmd);
  	}
  	public static function resetAndroidRemoteControl() {
  		log::remove('AndroidRemoteControl_reset');
  		$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../3rdparty/reset.sh';
  		$cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_reset') . ' 2>&1 &';
  		exec($cmd);
  	}
  	public static function statusAndroidRemoteControl($serviceName) {
  		log::remove('AndroidRemoteControl_status');
  		$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../3rdparty/status.sh ' . $serviceName;
  		$cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_status') . ' 2>&1 &';
  		exec($cmd);
  	}


    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {

    }

    public function postInsert() {

    }

    public function preSave() {
      if (!$this->getConfiguration('lastName') == ''){
  			if ($this->getConfiguration('name') !== $this->getConfiguration('lastName')) {
  				exec('echo Remove Service Name : ' . $this->getConfiguration('lastName') . ' >> ' . log::getPathToLog('AndroidRemoteControl_delete') . ' 2>&1 &');
  				$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../3rdparty/delete.sh ' . $this->getConfiguration('lastName');
  				$cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_delete') . ' 2>&1 &';
  				exec($cmd);
  				sleep(2);
  				$this->setConfiguration('lastName',$this->getConfiguration('name'));
  				exec('echo Setting Last Service Name : ' . $this->getConfiguration('lastName') . ' >> ' . log::getPathToLog('AndroidRemoteControl_delete') . ' 2>&1 &');
  			}
  		}
  		$this->setConfiguration('serviceName',$this->getConfiguration('name'));

    }

    public function postSave() {

      // foreach (eqLogic::byType('AndroidRemoteControl') as $AndroidRemoteControl) {
      //     $AndroidRemoteControl->getInformations();
      // }
      if ($this->getIsEnable()) {

  			$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../3rdparty/create.sh ' . $this->getConfiguration('name') . ' ' . $this->getConfiguration('ip');
  			$cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_create') . ' 2>&1 &';
  			exec('echo Create/Update Service Name : ' . $this->getConfiguration('name') . ' IP : ' . $this->getConfiguration('ip') . ' >> ' . log::getPathToLog('AndroidRemoteControl_create') . ' 2>&1 &');
  			exec($cmd);
  		} else {
  			$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../3rdparty/stop.sh ' . $this->getConfiguration('name');
  			$cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_status') . ' 2>&1 &';
  			exec($cmd);
  		}

/********************************Info***************************/
        $cmd = $this->getCmd(null, 'power_state');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('power_state');
            $cmd->setOrder(1);
            $cmd->setIsVisible(1);
            $cmd->setName(__('État', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('binary');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'ENERGY_STATE');
        $cmd->save();

        $cmd = $this->getCmd(null, 'encours');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('encours');
            $cmd->setOrder(3);
            $cmd->setIsVisible(1);
            $cmd->setName(__('encours', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('title_disable', 1);
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
        $cmd->setOrder(0);
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'LIGHT_STATE');
        $cmd->save();

      	$cmd = $this->getCmd(null, 'version');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('version');
            $cmd->setOrder(20);
            $cmd->setIsVisible(1);
            $cmd->setName(__('version', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'LIGHT_STATE');
        $cmd->save();

        $cmd = $this->getCmd(null, 'type');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('type');
            $cmd->setOrder(18);
            $cmd->setIsVisible(1);
            $cmd->setName(__('type', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'LIGHT_STATE');
        $cmd->save();

        $cmd = $this->getCmd(null, 'resolution');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('resolution');
            $cmd->setOrder(19);
            $cmd->setIsVisible(1);
            $cmd->setName(__('resolution', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('generic_type', 'LIGHT_STATE');
        $cmd->save();

 /*************************Action***************************/
        $cmd = $this->getCmd(null, 'power_set');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('power_set');
            $cmd->setOrder(4);
            $cmd->setIsVisible(1);
            $cmd->setName(__('power', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-power-off"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'home');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('home');
            $cmd->setOrder(6);
            $cmd->setIsVisible(1);
            $cmd->setName(__('home', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'back');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('back');
            $cmd->setOrder(5);
            $cmd->setIsVisible(1);
            $cmd->setName(__('back', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'enter');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('enter');
            $cmd->setOrder(15);
            $cmd->setIsVisible(1);
            $cmd->setName(__('enter', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'play');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('play');
            $cmd->setOrder(7);
            $cmd->setIsVisible(1);
            $cmd->setName(__('play', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-play"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'stop');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('stop');
            $cmd->setOrder(8);
            $cmd->setIsVisible(1);
            $cmd->setName(__('stop', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-stop"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'up');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('up');
            $cmd->setOrder(9);
            $cmd->setIsVisible(1);
            $cmd->setName(__('up', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-chevron-up"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'left');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('left');
            $cmd->setOrder(10);
            $cmd->setIsVisible(1);
            $cmd->setName(__('left', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-chevron-left"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'right');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('right');
            $cmd->setOrder(11);
            $cmd->setIsVisible(1);
            $cmd->setName(__('right', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-chevron-right"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'down');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('down');
            $cmd->setOrder(12);
            $cmd->setIsVisible(1);
            $cmd->setName(__('down', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-chevron-down"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'volume-');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('volume-');
            $cmd->setOrder(13);
            $cmd->setIsVisible(1);
            $cmd->setName(__('volume-', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-volume-down"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'volume+');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('volume+');
            $cmd->setOrder(14);
            $cmd->setIsVisible(1);
            $cmd->setName(__('volume+', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon','<i class="fa fa-volume-up"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();


        $infos = $this->getInfo();
        $this->updateInfo();
    }

    public function preUpdate() {
        if ($this->getConfiguration('ip') == '') {
            throw new Exception(__('L\'adresse IP doit être renseignée', __FILE__));
        }
    		if ($this->getConfiguration('name') === '') {
    			throw new Exception(__('Le champs Nom ne peut être vide', __FILE__));
    		}
    		// Si la chaîne contient des caractères spéciaux
    		if (!preg_match("#[a-zA-Z0-9_-]$#", $this->getConfiguration('name'))) {
        	throw new Exception(__('Le champs Nom ne peut contenir de caractères spéciaux', __FILE__));
    		}
    		// Si la chaîne contient des caractères spéciaux
    		if (preg_match("/\\s/", $this->getConfiguration('name'))) {
    			throw new Exception(__('Le champs Nom ne peut contenir d\'espaces', __FILE__));
    		}
    }

    public function postUpdate() {

    }

  	public function preRemove() {
  		$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../3rdparty/delete.sh ' . $this->getConfiguration('name');
  		$cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_delete') . ' 2>&1 &';
  		exec('echo Delete Service Name : ' . $this->getConfiguration('name') . ' >> ' . log::getPathToLog('AndroidRemoteControl_delete') . ' 2>&1 &');
  		exec($cmd);
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
     public function getInformations() {

       foreach ($this->getCmd() as $cmd) {
           $ip = $this->getConfiguration('ip');
           $name = $this->getConfiguration('name');
           $sudo = exec("\$EUID");

           if ($sudo == "0") {
             $state = exec("/etc/init.d/AndroidRemoteControl-service-$name status");
           } else {
             $state = exec("sudo /etc/init.d/AndroidRemoteControl-service-$name status");
           }

           $cmd->event($state);
       }
       if (is_object($state)) {
               return $state;
       } else {
         return '';
       }
     }
    public function getInfo() {
        $this->checkAndroidRemoteControlStatus();

        $power_state=substr(shell_exec("sudo adb shell dumpsys power -h | grep \"Display Power\" | cut -c22-"),0 , -1);
        $encours=substr(shell_exec("sudo adb shell dumpsys window windows | grep -E 'mFocusedApp'| cut -d / -f 1 | cut -d \" \" -f 7"), 0, -1);
      	$version=substr(shell_exec("sudo adb shell getprop ro.build.version.release"), 0, -1);
        $name=substr(shell_exec("sudo adb shell getprop ro.product.model"), 0, -1);
        $type=substr(shell_exec("sudo adb shell getprop ro.build.characteristics"), 0, -1);
        $resolution=substr(shell_exec("sudo adb shell getprop persist.sys.display.resolution"), 0, -1);

        return array('power_state' => $power_state, 'encours' => $encours, 'version' => $version, 'name' => $name, 'type' => $type, 'resolution' => $resolution);
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
          $cmd = $this->getCmd(null, 'encours');
        	switch ($infos['encours']) {
            	case "com.netflix.ninja":
                    $cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/netflix.png height="80" width="80">');
        	break;
            	case "tv.molotov.app":
                    $cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/molotov.png height="80" width="80">');
          	break;
            case "com.google.android.youtube.tv":
                    $cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/youtube.png height="80" width="80">');
          	break;
            case "com.google.android.leanbacklauncher":
                    $cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/home.png height="80" width="80">');
          	break;
            case "org.xbmc.kodi":
                    $cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/kodi.png height="80" width="80">');
          	break;
            case "com.amazon.amazonvideo.livingroom.nvidia":
                    $cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/amazonvideo.png height="80" width="80">');
          	break;
            case "org.videolan.vlc":
          			$cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/vlc.png height="80" width="80">');
          	break;
            case "com.vevo":
          			$cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/vevo.jpg height="80" width="80">');
          	break;
            case "com.plexapp.android":
          			$cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/plex.png height="80" width="80">');
          	break;
            case "com.spotify.tv.android":
          			$cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/spotify.png height="80" width="80">');
          	break;
            default:
                	$cmd->setDisplay('icon','<img src=plugins/AndroidRemoteControl/desktop/images/inconnu.png height="80" width="80">');
          	}
          $cmd->save();
        }
      if (isset($infos['version'])) {
            $this->checkAndUpdateCmd('version', $infos['version']);
        }
      if (isset($infos['name'])) {
            $this->checkAndUpdateCmd('name', $infos['name']);
        }

      if (isset($infos['type'])) {
            $this->checkAndUpdateCmd('type', $infos['type']);
        }
      if (isset($infos['name'])) {
            $this->checkAndUpdateCmd('resolution', $infos['resolution']);
        }

        throw new Exception(var_dump($infos), 1);
    }

    public function checkAndroidRemoteControlStatus() {
        $check=shell_exec("sudo adb devices | grep ".$this->getConfiguration('ip')." | cut -f2 | xargs");
      	echo $check;
        if(strstr($check, "offline"))
            throw new Exception("Votre appareil est détecté 'offline' par ADB.", 1);
        elseif(!strstr($check, "device")) {
            throw new Exception("Votre appareil n'est pas détecté par ADB.", 1);
        }elseif(strstr($check, "unauthorized")) {
            throw new Exception("Vous n'etes pas autorisé a vous connecter a cet appareil.", 1);
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
            shell_exec("sudo adb shell input keyevent KEYCODE_BUTTON_MEDIA_PLAY_PAUSE");
        } elseif ($this->getLogicalId() == 'up') {
            shell_exec("sudo adb shell input keyevent 19");
        } elseif ($this->getLogicalId() == 'down') {
            shell_exec("sudo adb shell input keyevent 20");
        } elseif ($this->getLogicalId() == 'left') {
            shell_exec("sudo adb shell input keyevent 21");
        } elseif ($this->getLogicalId() == 'right') {
            shell_exec("sudo adb shell input keyevent 22");
        } elseif ($this->getLogicalId() == 'back') {
            shell_exec("sudo adb shell input keyevent KEYCODE_BACK");
        } elseif ($this->getLogicalId() == 'enter') {
            shell_exec("sudo adb shell input keyevent KEYCODE_ENTER");
        } elseif ($this->getLogicalId() == 'volume+') {
            shell_exec("sudo adb shell input keyevent 24");
        } elseif ($this->getLogicalId() == 'volume-') {
            shell_exec("sudo adb shell input keyevent 25");
        } elseif ($this->getLogicalId() == 'netflix') {
            shell_exec("sudo adb shell am start com.netflix.ninja/.MainActivity");
        } elseif ($this->getLogicalId() == 'youtube') {
            shell_exec("sudo adb shell monkey -p com.google.android.youtube.tv -c android.intent.category.LAUNCHER 1");
        } elseif ($this->getLogicalId() == 'plex') {
            shell_exec("sudo adb shell monkey -p com.plexapp.android -c android.intent.category.LAUNCHER 1");
        } elseif ($this->getLogicalId() == 'kodi') {
            shell_exec("sudo adb shell monkey -p org.xbmc.kodi -c android.intent.category.LAUNCHER 1");
        } elseif ($this->getLogicalId() == 'molotov') {
            shell_exec("sudo adb shell am start tv.molotov.app/tv.molotov.android.tv.SplashActivity");
        } elseif ($this->getLogicalId() == 'spotify') {
            shell_exec("sudo adb shell monkey -p com.plexapp.android -c android.intent.category.LAUNCHER 1");
        }

        $eqLogic->updateInfo();
    }

    /*     * **********************Getteur Setteur*************************** */
}
