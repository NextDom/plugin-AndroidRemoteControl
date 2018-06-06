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



/**
 * Mock de la classe log
 */
class log
{
    /**
     * Ajoute un message d'erreur dans les logs
     *
     * @param string $msg Message
     * @param string $code Code de l'erreur
     */
    public static function add($plugin, $channel, $msg)
    {
        MockedActions::add('log_add', array('msg' => $msg, 'plugin' => $plugin, 'channel' => $channel));
    }
}
