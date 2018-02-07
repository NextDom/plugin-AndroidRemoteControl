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
            $cmd->setDisplay('icon','<i class=\"fa fa-play\"><\/i>');
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

    public function toHtml($_version = 'dashboard') { //Fini
            if ($this->getIsEnable() != 1) {
                return '';
            }
    		if (!$this->hasRight('r')) {
    			return '';
    		}

    		$version = jeedom::versionAlias($_version, false);
    		if ($this->getDisplay('showOn' . $version, 1) == 0) {
    			return '';
    		}

    		$replace = array(
    			'#id#' => $this->getId(),
    			'#name#' => $this->getName(),
    			'#synoid#' => $this->getlogicalId(),
    			'#name_display#' => $this->getName(),
    			'#hideEqLogicName#' => '',
    			'#eqLink#' => ($this->hasRight('w')) ? $this->getLinkToConfiguration() : '#',
    			'#category#' => $this->getPrimaryCategory(),
    			'#uid#' => 'synovideo' . $this->getId() . self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER,
    			'#color#' => '#ffffff',
    			'#border#' => 'none',
    			'#border-radius#' => '4px',
    			'#style#' => '',
    			'#max_width#' => '650px',
    			'#logicalId#' => $this->getLogicalId(),
    			'#object_name#' => '',
    			'#height#' => $this->getDisplay('height', 'auto'),
    			'#width#' => $this->getDisplay('width', 'auto'),
    			'#uid#' => 'eqLogic' . $this->getId() . self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER,
    			'#refresh_id#' => '',
    			'#version#' => $_version,
    			'#text-color#' => $this->getDisplay('pgTextColor'),
    			'#background-color#' => $this->getDisplay('pgBackColor'),
    			'#hideThumbnail#' => 0,
    			'#disable#' => 0
    		);

    		if ($this->getDisplay('background-color-default' . $version, 1) == 1) {
    			if (isset($_default['#background-color#'])) {
    				$replace['#background-color#'] = $_default['#background-color#'];
    			} else {
    				$replace['#background-color#'] = $this->getBackgroundColor($version);
    			}
    		} else {
    			$replace['#background-color#'] = ($this->getDisplay('background-color-transparent' . $_version, 0) == 1) ? 'transparent' : $this->getDisplay('background-color' . $version, $this->getBackgroundColor($version));
    		}
    		if ($this->getDisplay('color-default' . $version, 1) != 1) {
    			$replace['#color#'] = $this->getDisplay('color' . $version, '#ffffff');
    		}
    		if ($this->getDisplay('border-default' . $version, 1) != 1) {
    			$replace['#border#'] = $this->getDisplay('border' . $version, 'none');
    		}
    		if ($this->getDisplay('border-radius-default' . $version, 1) != 1) {
    			$replace['#border-radius#'] = $this->getDisplay('border-radius' . $version, '4') . 'px';
    		}

    		if ($_version == 'dview' || $_version == 'mview') {
    			$object = $this->getObject();
    			$replace['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace['#name#'] : $replace['#name#'];
    		}
    		if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotShowNameOnView') == 1) {
    			$replace['#name#'] = '';
    		}
    		if (($_version == 'mobile' || $_version == 'dashboard') && $this->getDisplay('doNotShowNameOnDashboard') == 1) {
    			$replace['#name#'] = '';
    		}

    		if ($this->getDisplay('showObjectNameOn' . $version, 0) == 1) {
    			$object = $this->getObject();
    			$replace['#object_name#'] = (is_object($object)) ? '(' . $object->getName() . ')' : '';
    		}
    		if ($this->getDisplay('showNameOn' . $version, 1) == 0) {
    			$replace['#hideEqLogicName#'] = 'display:none;';
    		}
    		$default_opacity = config::byKey('widget::background-opacity');
    		if (isset($_SESSION) && isset($_SESSION['user']) && is_object($_SESSION['user']) && $_SESSION['user']->getOptions('widget::background-opacity::' . $version, null) !== null) {
    			$default_opacity = $_SESSION['user']->getOptions('widget::background-opacity::' . $version);
    		}
    		$opacity = $this->getDisplay('background-opacity' . $version, $default_opacity);
    		if ($replace['#background-color#'] != 'transparent' && $opacity != '' && $opacity < 1) {
    			list($r, $g, $b) = sscanf($replace['#background-color#'], "#%02x%02x%02x");
    			$replace['#background-color#'] = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')';
    		}
    		if ($this->getDisplay('isLight') == 1) {
    			$replace['#hideThumbnail#'] = '1';
    		}
    		$cmd_state = $this->getCmd(null, 'state');
    		if (is_object($cmd_state)) {
    			$replace['#state#'] = $cmd_state->execCmd();
    			if ($replace['#state#'] == __('Lecture', __FILE__)) {
    				$replace['#state_nb#'] = 1;
    			} else {
    				$replace['#state_nb#'] = 0;
    			}
    		}
    		$cmd_track_title = $this->getCmd(null, 'movie_title');
    		if (is_object($cmd_track_title)) {
    			$replace['#title#'] = $cmd_track_title->execCmd();
    		}
    		if (strlen($replace['#title#']) > 15) {
    			$replace['#title#'] = '<marquee behavior="scroll" direction="left" scrollamount="2">' . $replace['#title#'] . '</marquee>';
    		}
    		$cmd_track_image = $this->getCmd(null, 'movie_image');
    		if (is_object($cmd_track_image)) {
    			//$img = dirname(__FILE__) . '/../../../../plugins/synovideo/doc/images/syno_poster_' . $this->getId() . '.jpg';
    			$img=$cmd_track_image->execCmd();
    			if (file_exists(dirname(__FILE__) . '/../../../../'. $img) && filesize(dirname(__FILE__) . '/../../../../'. $img) > 110) {
    			//	$replace['#thumbnail#'] = 'plugins/synovideo/doc/images/syno_poster_' . $this->getId() . '.jpg?time=' .time();
    				$replace['#thumbnail#'] = $img . '?time=' .time();
    			} else {
    				$replace['#thumbnail#'] = 'plugins/synovideo/doc/images/syno_poster_default.png?time=' .time();
    			}
    		}

    		$replace['#seekable#'] = $this->getConfiguration('seekable');

    		$replace['#blockVolume#'] = $this->getConfiguration('volume_adjustable');

    		$cmd_volume = $this->getCmd(null, 'volume');
    		if (is_object($cmd_volume)) {
    			$replace['#volume#'] = $cmd_volume->execCmd();
    		}

    		$cmd_position = $this->getCmd(null, 'position');
    		$replace['#position#'] = intval($cmd_position->execCmd())*1000;
    		$cmd_duration = $this->getCmd(null, 'duration');
    		$replace['#duration#'] = intval($cmd_duration->execCmd())*1000;
    		if (is_object($cmd_position) && is_object($cmd_duration)) {
    			$position100= (intval($cmd_position->execCmd())/intval($cmd_duration->execCmd()))*100;
    			$replace['#position100#'] = $position100;
    		}
    		$cmd_setVolume = $this->getCmd(null, 'setVolume');
    		if (is_object($cmd_setVolume)) {
    			$replace['#volume_id#'] = $cmd_setVolume->getId();
    		}
    		$cmd_setPosition = $this->getCmd(null, 'setPosition');
    		if (is_object($cmd_setPosition)) {
    			$replace['#position_id#'] = $cmd_setPosition->getId();
    		}

    		$volume=cache::byKey('SYNO.tmp.volume');
    		if (is_object($volume)) {
    			$replace['#onmute#'] = true;
    		}
    		foreach ($this->getCmd('action') as $cmd) {
    			$replace['#cmd_' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
    		}
    		$parameters = $this->getDisplay('parameters');
    		if (is_array($parameters)) {
    			foreach ($parameters as $key => $value) {
    				$replace['#' . $key . '#'] = $value;
    			}
    		}

    		$replace['#IsMultiple#'] = $this->getConfiguration('is_multiple');


    		$_version = jeedom::versionAlias($_version);
    		return template_replace($replace, getTemplate('core', $_version, 'synovideo', 'synovideo'));
    	}

    }
}
