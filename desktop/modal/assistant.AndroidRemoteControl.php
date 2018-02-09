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
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id='div_assisantAndroidRemoteControlAlert' style="display: none;"></div>
<!-- <button id="go" class="btn btn-primary" type="button" >{{Rechercher}}</button>
<input id="search" type="text" name="page_no" size="3"/> -->

<!--changed input type to button and added an id of go-->
<!-- <input id="go" type="button" name="goto" value="Go"/> -->
<!-- <br/><br/><br/> -->
<div id="contentAssistant" style="height: 100%">
</div>
<script type="text/javascript" src="/plugins/AndroidRemoteControl/3rdparty/hilitor.js"></script>
<script>
	$("#go").click(function(){
		var search = document.getElementById("search").value;
		var myHilitor = new Hilitor2("contentAssistant","docIspyConnect");
  	myHilitor.apply(search);
	});
</script>
