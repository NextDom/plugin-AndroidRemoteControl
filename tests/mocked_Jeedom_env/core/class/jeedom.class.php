
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
 * Mock de la classe Jeedom
 */
class jeedom
{
    /**
     * @var bool Réponse pour la commande jeedom::isCapable
     */
    public static $isCapableAnswer = false;

    /**
     * @var string Nom du matériel reconnu par Jeedom
     */
    public static $hardwareName;

    /**
     * Obtenir le nom du matériel.
     *
     * @return string Valeur de jeedom::$hardwareName
     */
    public static function getHardwareName()
    {
        return jeedom::$hardwareName;
    }

    /**
     * Test si Jeedom peut exécutée une commande.
     *
     * @param string $str Nom de la commande à utiliser (inutilisé)
     *
     * @return bool Valeur de jeedom::$isCapableAnswer
     */
    public static function isCapable($str)
    {
        return self::$isCapableAnswer;
    }
}
