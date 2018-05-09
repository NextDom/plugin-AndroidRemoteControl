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
require_once __DIR__ . '/../../../../core/php/core.inc.php';
$url = __DIR__ . '/../../3rdparty/appli.json'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$json_a = json_decode($data); // decode the JSON feed

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

    public static function resetAndroidRemoteControl($ip_address)
    {
        log::remove('AndroidRemoteControl_reset');
        $cmd = '/bin/bash ' . __DIR__ . '/../../3rdparty/reset.sh';
        $cmd .= ' >> ' . log::getPathToLog('AndroidRemoteControl_reset') . ' 2>&1 &';
        exec($cmd);
    }

    public function postSave()
    {
        $data = file_get_contents(dirname(__FILE__)  . '/../../3rdparty/appli.json');
        $data2 = file_get_contents(dirname(__FILE__)  . '/../../3rdparty/commandes.json');
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
        $volume->setConfiguration('repeatEventManagement', 'never');
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
        $cmd->setValue($volume->getId());
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
        $title        = substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell dumpsys bluetooth_manager | grep MediaAttributes | cut -d: -f3"), 0, -2);
        $volume       = substr(shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell dumpsys audio | grep -A 4 STREAM_MUSIC |grep Current | cut -c26-27"), 0, -1);;
        $play_state  = substr(shell_exec($sudo_prefix . "adb -s " . $ip_address . ":5555  shell dumpsys bluetooth_manager | grep mCurrentPlayState | cut -d,  -f1 | cut -c43-"), 0, -1);
        $battery_level  = substr(shell_exec($sudo_prefix . "adb -s " . $ip_address . ":5555 shell dumpsys battery | grep level | cut -d: -f2"), 0, -1);

        return array('power_state' => $power_state, 'encours' => $encours, 'version_android' => $version_android, 'name' => $name, 'type' => $type, 'resolution' => $resolution, 'disk_total' => $disk_total, 'disk_free' => $disk_free, 'title' => $title, 'volume' => $volume, 'play_state' => $play_state, 'battery_level' => $battery_level);
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
            $this->checkAndUpdateCmd('play_state', $infos['play_state']);
        }
        if (isset($infos['battery_level'])) {
            $this->checkAndUpdateCmd('battery_level', $infos['battery_level']);
        }
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
        if (strstr($check, "offline")) {
            $cmd = $this->getCmd(null, 'encours');
            log::add('AndroidRemoteControl', 'info', 'Votre appareil est offline');
            log::add('AndroidRemoteControl', 'info', 'Relance du service ADB');
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/erreur.png');
            $cmd->save();
            exec('../3rdparty/reset.sh');
            log::add('AndroidRemoteControl', 'info', 'Connection a Android');
            shell_exec($sudo_prefix . "adb connect " . $ip_address);
        } elseif (!strstr($check, "device")) {
            $cmd = $this->getCmd(null, 'encours');
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/erreur.png');
            $cmd->save();
            log::add('AndroidRemoteControl', 'info', 'Votre appareil n\'est pas détecté par ADB. Tentative de reconnection, veuillez réessayer');
            log::add('AndroidRemoteControl', 'info', 'Relance du service ADB');
            exec('../3rdparty/reset.sh');
            log::add('AndroidRemoteControl', 'info', 'Connection a Android');
            shell_exec($sudo_prefix . "adb connect " . $ip_address);
        } elseif (strstr($check, "unauthorized")) {
            $cmd = $this->getCmd(null, 'encours');
            $cmd->setDisplay('icon', 'plugins/AndroidRemoteControl/desktop/images/erreur.png');
            $cmd->save();
            log::add('AndroidRemoteControl', 'info', 'Votre connection n\'est pas autorisé');
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
                if($cmd->execCmd() <= 2){
                    $replace['#play_pause#'] = '"fa fa-play  fa-lg"';
                }else{
                    $replace['#play_pause#'] = '"fa fa-pause  fa-lg"; style="color:green"';
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

class AndroidRemoteControlCmd extends cmd
{

    public function execute(array $_options = array())
    {
        $ARC = $this->getEqLogic();
        $ARC->checkAndroidRemoteControlStatus();

        $sudo = exec("\$EUID");
        if ($sudo != "0") {
            $sudo_prefix = "sudo ";
        }
        $ip_address = $ARC->getConfiguration('ip_address');


        $data = file_get_contents(__DIR__ . '/../../3rdparty/appli.json');
        $data2 = file_get_contents(__DIR__ . '/../../3rdparty/commandes.json');
        $json= json_encode(array_merge(json_decode($data, true),json_decode($data2, true)));
        $json_a = json_decode($json);
        foreach ($json_a as $json_b) {
            if (stristr($this->getLogicalId(), $json_b->name)){
                log::add('AndroidRemoteControl', 'info', 'Command '. $json_b->commande. ' sent to android device at ip address : ' . $ip_address);
                shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 " . $json_b->commande);
            }
        }
        if (stristr($this->getLogicalId(), 'setVolume')){
            shell_exec($sudo_prefix . "adb -s ".$ip_address.":5555 shell service call audio 3 i32 3 i32 " . $_options['slider']);
        }

        $ARC->updateInfo();
    }

}
