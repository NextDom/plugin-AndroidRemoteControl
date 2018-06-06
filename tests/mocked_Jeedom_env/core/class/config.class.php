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




require_once('../../mocked_core.php');

/**
 * Mock de la classe config
 */
class config
{
    /**
     * @var array Informations des logs systèmes
     */
    public static $byKeyData = array(
        'log::level::scenario' => array(
            '100' => 1,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::plugin' => array(
            '100' => 0,
            '200' => 1,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::market' => array(
            '100' => 0,
            '200' => 0,
            '300' => 1,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::api' => array(
            '100' => 0,
            '200' => 0,
            '300' => 0,
            '400' => 1,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::connection' => array(
            '100' => 0,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 1,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::interact' => array(
            '100' => 0,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 1,
            'default' => 0
        ),
        'log::level::tts' => array(
            '100' => 0,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 1
        ),
        'log::level::report' => array(
            '100' => 0,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::event' => array(
            '100' => 1,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::thetemplate' => array(
            '100' => 1,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::IOptimize' => array(
            '100' => 0,
            '200' => 1,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::supa_plugin' => array(
            '100' => 0,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 1,
            'default' => 0
        )
    );

    /**
     * @var array Informations d'un plugin
     */
    public static $byKeyPluginData = array();

    /**
     * Obtenir une information à partir de l'identifiant
     *
     * @param string $key Clé de l'information
     * @param string $plugin Identifiant du plugin
     * @return array Information sur le slogs
     */
    public static function byKey($key, $plugin = 'core')
    {
        $result = false;
        if ($plugin == 'core') {
            $result = config::$byKeyData[$key];
        } else {
            if (isset(config::$byKeyPluginData[$plugin])) {
                $result = config::$byKeyPluginData[$plugin][$key];
            }
        }
        return $result;
    }

    /**
     *
     *
     * @param $key
     * @param $data
     * @param null $plugin
     */
    public static function save($key, $data, $plugin = null)
    {
        MockedActions::add('save', array('key' => $key, 'data' => $data, 'plugin' => $plugin));
    }

    public static function remove($key, $plugin = null)
    {
        MockedActions::add('remove', array('key' => $key, 'plugin' => $plugin));
    }
}
