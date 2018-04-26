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

class AndroidRemoteControl extends eqLogic
{
      /*     * *************************Attributs****************************** */
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
          	$eqLogic->refreshWidget();
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
        $resource_path = realpath(dirname(__FILE__) . '/../../3rdparty');
        passthru('/bin/bash ' . $resource_path . '/install.sh ' . $resource_path . ' > ' . log::getPathToLog('AndroidRemoteControl_dep') . ' 2>&1 &');
    }

    /*     * ***********************Methode static*************************** */
    public static function resetAndroidRemoteControl($ip_address)
    {
        log::remove('AndroidRemoteControl_reset'); 
        $cmd = '/bin/bash ' . dirname(__FILE__) . '/../../3rdparty/reset.sh';
        $cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_reset') . ' 2>&1 &';
        exec($cmd);
    }

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert()
    {

    }

    public function postInsert()
    {

    }

    public function preSave()
    {

    }

    public function postSave()
    {

        /*         * ******************************Info************************** */
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
        $cmd->save();

        $cmd = $this->getCmd(null, 'encours');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('encours');
            $cmd->setIsVisible(1);
            $cmd->setName(__('encours', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setTemplate('dashboard', 'encours');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->setDisplay('title_disable', 1);
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
        $cmd->save();

        $cmd = $this->getCmd(null, 'version_android');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('version_android');
            $cmd->setIsVisible(1);
            $cmd->setName(__('version_android', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'type');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('type');
            $cmd->setIsVisible(1);
            $cmd->setName(__('type', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'resolution');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('resolution');
            $cmd->setIsVisible(1);
            $cmd->setName(__('resolution', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();
      
      	        $cmd = $this->getCmd(null, 'disk_total');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('disk_total');
            $cmd->setIsVisible(1);
            $cmd->setName(__('disk_total', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();
      
              $cmd = $this->getCmd(null, 'disk_free');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('disk_free');
            $cmd->setIsVisible(1);
            $cmd->setName(__('disk_free', __FILE__));
        }
        $cmd->setType('info');
        $cmd->setSubType('string');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        /*         * ***********************Action************************** */
        $cmd = $this->getCmd(null, 'power_set');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('power_set');
            $cmd->setIsVisible(1);
            $cmd->setName(__('power', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-power-off"></i>');
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

        $cmd = $this->getCmd(null, 'back');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('back');
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
            $cmd->setIsVisible(1);
            $cmd->setName(__('play', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-play"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'stop');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('stop');
            $cmd->setIsVisible(1);
            $cmd->setName(__('stop', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-stop"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'up');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('up');
            $cmd->setIsVisible(1);
            $cmd->setName(__('up', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-chevron-up"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'left');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('left');
            $cmd->setIsVisible(1);
            $cmd->setName(__('left', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-chevron-left"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'right');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('right');
            $cmd->setIsVisible(1);
            $cmd->setName(__('right', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-chevron-right"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'down');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('down');
            $cmd->setIsVisible(1);
            $cmd->setName(__('down', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-chevron-down"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'volume-');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('volume-');
            $cmd->setIsVisible(1);
            $cmd->setName(__('volume-', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-volume-down"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'volume+');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('volume+');
            $cmd->setIsVisible(1);
            $cmd->setName(__('volume+', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<i class="fa fa-volume-up"></i>');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'youtube');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('youtube');
            $cmd->setIsVisible(1);
            $cmd->setName(__('youtube', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/youtube.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'molotov');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('molotov');
            $cmd->setIsVisible(1);
            $cmd->setName(__('molotov', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/molotov.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'plex');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('plex');
            $cmd->setIsVisible(1);
            $cmd->setName(__('plex', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/plex.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'kodi');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('kodi');
            $cmd->setIsVisible(1);
            $cmd->setName(__('kodi', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/kodi.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'netflix');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('netflix');
            $cmd->setIsVisible(1);
            $cmd->setName(__('netflix', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/netflix.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();
        
        $cmd = $this->getCmd(null, 'spotify');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('spotify);
            $cmd->setIsVisible(1);
            $cmd->setName(__('spotify', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/spotify.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'amazonvideo');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('amazonvideo');
            $cmd->setIsVisible(1);
            $cmd->setName(__('amazonvideo', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/amazonvideo.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();

        $cmd = $this->getCmd(null, 'vevo');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('vevo');
            $cmd->setIsVisible(1);
            $cmd->setName(__('vevo', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/vevo.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();
      
       $cmd = $this->getCmd(null, 'ted');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('ted');
            $cmd->setIsVisible(1);
            $cmd->setName(__('ted', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/ted.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();
		
  
  		 $cmd = $this->getCmd(null, 'mytf1');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('mytf1');
            $cmd->setIsVisible(1);
            $cmd->setName(__('mytf1', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/tf1.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();
      
       $cmd = $this->getCmd(null, 'm6replay');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('m6replay');
            $cmd->setIsVisible(1);
            $cmd->setName(__('m6replay', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/m6replay.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();
      
       $cmd = $this->getCmd(null, 'dsvideo');
        if (!is_object($cmd)) {
            $cmd = new AndroidRemoteControlCmd();
            $cmd->setLogicalId('dsvideo');
            $cmd->setIsVisible(1);
            $cmd->setName(__('dsvideo', __FILE__));
        }
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setDisplay('icon', '<img src=plugins/AndroidRemoteControl/desktop/images/vevo.png height="15" width="15">');
        $cmd->setEqLogic_id($this->getId());
        $cmd->save();
 
}

public function preUpdate()
{
    if ($this->getConfiguration('ip_address') == '') {
        throw new Exception(__('L\'adresse IP doit être renseignée', __FILE__));
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

    $power_state = substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell dumpsys power -h | grep \"Display Power\" | cut -c22-"), 0, -1);
    $encours     = substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell dumpsys window windows | grep -E 'mFocusedApp'| cut -d / -f 1 | cut -d \" \" -f 7"), 0, -1);
    $version_android     = substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell getprop ro.build.version.release"), 0, -1);
    $name        = substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell getprop ro.product.model"), 0, -1);
    $type        = substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell getprop ro.build.characteristics"), 0, -1);
  	$resolution  = substr(shell_exec($sudo_prefix . "adb -s " . $ip_address . ":5555 shell dumpsys window displays | grep init | cut -c45-53"), 0, -1);
    $disk_free = substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell dumpsys diskstats | grep Data-Free | cut -c44-46"), 0, -1);
  	$disk_total = round(substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell dumpsys diskstats | grep Data-Free | cut -c25-33"), 0, -1)/1000000, 1);
  
    return array('power_state' => $power_state, 'encours' => $encours, 'version_android' => $version_android, 'name' => $name, 'type' => $type, 'resolution' => $resolution, 'disk_total' => $disk_total, 'disk_free' => $disk_free);
}

public function updateInfo()
{
    try {
        $infos = $this->getInfo();
    } catch (Exception $e) {
        return;
    }

    if (!is_array($infos)) {
        return;
    }

    if (isset($infos['power_state'])) {
        $this->checkAndUpdateCmd('power_state', ($infos['power_state'] == "ON") ? 1 : 0 );
    }
    if (isset($infos['encours'])) {
        $cmd = $this->getCmd(null, 'encours');
        switch (true) {
            case stristr($infos['encours'], "netflix"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/netflix.png" ');
            break;
            case stristr($infos['encours'], "molotov"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/molotov.png" ');
            $cmd->toHtml('dashboard');
            break;
            case stristr($infos['encours'], "youtube"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/youtube.png" ');
            break;
            case stristr($infos['encours'], "launcher"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/home.png" ');
            break;
            case stristr($infos['encours'], "kodi"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/kodi.png" ');
            break;
            case stristr($infos['encours'], "amazon"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/amazonvideo.png" ');
            break;
            case stristr($infos['encours'], "vlc") :
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/vlc.png" ');
            break;
            case stristr($infos['encours'], "vevo"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/vevo.jpg" ');
            break;
            case stristr($infos['encours'], "plex"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/plex.png" ');
            break;
            case stristr($infos['encours'], "spotify"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/spotify.png" ');
            break;
            case stristr($infos['encours'], "ted"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/ted.png" ');
            break;
            case stristr($infos['encours'], "mytf1"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/tf1.png" ');
            break;
            case stristr($infos['encours'], "m6replay"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/m6replay.png" ');
            break;
            case stristr($infos['encours'], "dsvideo"):
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/dsvideo.png" ');
            break;
          	default:
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/inconnu.png" ');
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

        #throw new Exception(var_dump($infos), 1);
    }

    public function checkAndroidRemoteControlStatus()
    {
        $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
        $ip_address = $this->getConfiguration('ip_address');
        $check = shell_exec($sudo_prefix . "adb devices | grep " . $ip_address . " | cut -f2 | xargs");
        echo $check;
        if (strstr($check, "offline"))
        	throw new Exception("Votre appareil est détecté 'offline' par ADB.", 1);
        elseif (!strstr($check, "device")) {
           shell_exec($sudo_prefix ."adb connect " . $ip_address);
            throw new Exception("Votre appareil n'est pas détecté par ADB. Tentative de reconnection, veuillez réessayer", 1);
        } elseif (strstr($check, "unauthorized")) {
            throw new Exception("Vous n'etes pas autorisé a vous connecter a cet appareil.", 1);
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
            if ($cmd->getIsHistorized() == 1) {
                $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
            }
        }

        foreach ($this->getCmd('action') as $cmd) {
        	   $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
          	   $replace['#' . $cmd->getLogicalId() . '_id_display#'] = (is_object($cmd) && $cmd->getIsVisible()) ? '#' . $cmd->getId() . "_id_display#" : 'none';
            
        }

         return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'eqLogic', 'AndroidRemoteControl')));
    }

}

class AndroidRemoteControlCmd extends cmd
{
  
    public function execute($_options = array())
    {
        $ARC = $this->getEqLogic();
        $ARC->checkAndroidRemoteControlStatus();

        $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
        $ip_address = $ARC->getConfiguration('ip_address');
        log::add('AndroidRemoteControl', 'info', 'Command sent to android device at ip address : ' . $ip_address);
        if ($this->getLogicalId() == 'power_set') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent 26");
        } elseif ($this->getLogicalId() == 'home') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent 3");
        } elseif ($this->getLogicalId() == 'play') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent KEYCODE_BUTTON_MEDIA_PLAY_PAUSE");
        } elseif ($this->getLogicalId() == 'up') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent 19");
        } elseif ($this->getLogicalId() == 'down') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent 20");
        } elseif ($this->getLogicalId() == 'left') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent 21");
        } elseif ($this->getLogicalId() == 'right') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent 22");
        } elseif ($this->getLogicalId() == 'back') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent KEYCODE_BACK");
        } elseif ($this->getLogicalId() == 'enter') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent KEYCODE_ENTER");
        } elseif ($this->getLogicalId() == 'volume+') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent 24");
        } elseif ($this->getLogicalId() == 'volume-') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell input keyevent 25");
        } elseif ($this->getLogicalId() == 'netflix') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell am start com.netflix.ninja/.MainActivity");
        } elseif ($this->getLogicalId() == 'youtube') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p com.google.android.youtube.tv -c android.intent.category.LAUNCHER 1");
        } elseif ($this->getLogicalId() == 'plex') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p com.plexapp.android -c android.intent.category.LAUNCHER 1");
        } elseif ($this->getLogicalId() == 'kodi') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p org.xbmc.kodi -c android.intent.category.LAUNCHER 1");
        } elseif ($this->getLogicalId() == 'molotov') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell am start tv.molotov.app/tv.molotov.android.tv.SplashActivity");
        } elseif ($this->getLogicalId() == 'spotify') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p com.spotify.tv.android -c android.intent.category.LAUNCHER 1");
        } elseif ($this->getLogicalId() == 'amazonvideo') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p com.amazon.amazonvideo.livingroom.nvidia -c android.intent.category.LAUNCHER 1");
        } elseif ($this->getLogicalId() == 'vevo') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p com.vevo -c android.intent.category.LAUNCHER 1");
        }elseif ($this->getLogicalId() == 'mytf1') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p fr.tf1.mytf1 -c android.intent.category.LAUNCHER 1");
        }elseif ($this->getLogicalId() == 'm6replay') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p fr.m6.m6replay.by -c android.intent.category.LAUNCHER 1");
        }elseif ($this->getLogicalId() == 'dsvideo') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p com.synology.dsvideo -c android.intent.category.LAUNCHER 1");
        }elseif ($this->getLogicalId() == 'ted') {
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell monkey -p com.ted.android.tv -c android.intent.category.LAUNCHER 1");
        }
        $ARC->updateInfo();
    }

}
