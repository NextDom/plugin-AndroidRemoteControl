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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
include_file('desktop', 'AndroidRemoteControlForJeedomConfiguration', 'js', 'AndroidRemoteControl');

?>

<form class="form-horizontal">

  <div class="panel-body">
    <div class="form-group">
      <label class="col-sm-2 control-label">{{Réparer :}}</label>
			<div class="col-sm-4">
		<a class="btn btn-warning" id="bt_resetAndroidRemoteControl"><i class="fa fa-check"></i> {{Relancer le service ADB}}</a>
    </div>      
  <div class="form-group">
  	<label class="col-sm-2 control-label">{{Informations :}}</label>
      <div class="col-sm-4">
 		<button id="show-nextdom-modal" class="btn btn-success"><i class="fa fa-users"></i> {{NextDom}}</button>     
    </div>
  </div>
  <div class="panel panel-info" style="height: 100%;">
	<div class="panel-heading" role="tab">
		<h4 class="panel-title">
			Dons aux developpeurs
		</h4>
	</div>
	<div class="form-group">
		<div class="col-lg-8" style="margin-top:9px">

			<li>{{Developpement}} : Byackee  >
			<a class="btn-link" id="bt_paypal" href="https://paypal.me/byackee" target="_new" >
				<img src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" alt="{{Faire un don via Paypal au développeur Byackee}}">
			</a>
			</li><br>
  <li>{{Assistant de configuration}} : Slobberbone   >
			<a class="btn-link" id="bt_paypal" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=PH295BMQ33EFN&lc=FR&item_name=Plugin%20Jeedom%20AndroidRC&currency_code=EUR" target="_new" >
				<img src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" alt="{{Faire un don via Paypal au développeur Slobberbone}}">
			</a>
			</li>
		</div>	
	</div>
  </div>
</form>
<script>
  $('#bt_resetAndroidRemoteControl').on('click',function(){
  		bootbox.confirm('{{Etes-vous sûr de vouloir relancer le service ADB ?}}', function (result) {
  			if (result) {
              	$.post({
        url: 'plugins/AndroidRemoteControl/core/ajax/AndroidRemoteControl.ajax.php',
        data: {
            action: 'resetADB'
        },
        dataType: 'json',
        success: function (data, status) {
            // Test si l'appel a échoué
            if (data.state !== 'ok' || status !== 'success') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
            }
            else {

            }
        },
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        }
    });	

  			}
  		});
  	});
</script>
