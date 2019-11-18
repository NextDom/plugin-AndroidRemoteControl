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

include_file('core', 'authentification', 'php');

if (!isConnect('admin')) {
    throw new \Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="div_pluginAlternativeMarketForJeedomAlert"></div>
<div id="nextdom-modal">
    <div class="panel panel-info">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Qui sommes-nous ?}}
            </h4>
        </div>
        <div class="panel-body">
            {{Nous sommes un regroupement de développeurs, passionnés, adeptes de la solution Open Source de domotique Jeedom réalisée par une équipe géniale ! Nous n'avons aucun lien commercial ou autre avec Jeedom SAS, hormis la volonté de faire grandir la solution.}}
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Où nous trouver ?}}
            </h4>
        </div>
        <div class="panel-body">
            <ul class="list-group">
                <li class="list-group-item"><i class="fa fa-github"></i><span> {{GitHub}} : </span><a href="https://github.com/NextDom">https://github.com/NextDom</a></li>
                <li class="list-group-item"><i class="fa fa-globe"></i><span> {{Site internet}} : </span><a href="https://nextdom.github.io">https://nextdom.github.io</a></li>
                <li class="list-group-item"><i class="fa fa-comments"></i><span> {{Gitter}} : </span><a href="https://gitter.im/NextDom">https://gitter.im/NextDom</a></li>
            </ul>
        </div>
    </div>
</div>

