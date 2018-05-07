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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>



<form class="form-horizontal">

  <div class="panel-body">
    <div class="form-group">
      <label class="col-sm-2 control-label">{{Réparer :}}</label>
			<div class="col-sm-4">
				<a class="btn btn-success" id="bt_resetAndroidRemoteControl"><i class="fa fa-check"></i> {{Relancer le service ADB}}</a>
			</div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-4">
      </div>
      <label class="col-sm-2 control-label">{{Informations :}}</label>
      <div class="col-sm-4">
        <a class="btn btn-success" href="https://NextDom.github.io/NextDom/"><i class="fa fa-question-circle"></i> {{NextDom}}</a>
      </div>
    </div>
  </div>
</form>
<script>
  $('#bt_resetAndroidRemoteControl').on('click',function(){
  		bootbox.confirm('{{Etes-vous sûr de vouloir relancer le service ADB ?}}', function (result) {
  			if (result) {
  				$('#md_modal').dialog({title: "{{Reset}}"});
  				$('#md_modal').load('index.php?v=d&plugin=AndroidRemoteControl&modal=reset.AndroidRemoteControl').dialog('open');
  			}
  		});
  	});
</script>
