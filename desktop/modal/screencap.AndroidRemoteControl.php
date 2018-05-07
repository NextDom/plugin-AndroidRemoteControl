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

if (init('id') == '') {
    throw new Exception('{{L\'id de l\'opération ne peut etre vide : }}' . init('op_id'));
}

$id = init('id');
$ip_address = init('ip');

?>
<div class="container-modal">
<div id="image"></div>
</div>
  
<script>
$(document).ready(function() {
<?php shell_exec("sudo adb -s $ip_address:5555 shell screencap -p > /var/www/html/plugins/AndroidRemoteControl/3rdparty/screencap$id.png"); ?>
$('#image').html('<img src="/plugins/AndroidRemoteControl/3rdparty/screencap<?php echo  $id ?>.png">');

});
</script>
<style>
  img {
    max-width: 100%;
    max-height: 100%;
}
</style>