<?php
/*
    Copyright (C) 2013-2016 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?php
include "/usr/share/fruitywifi/www/config/config.php";
include "/usr/share/fruitywifi/www/modules/captive/_info_.php";
include "/usr/share/fruitywifi/www/functions.php";
include "../_portal_functions.php";

if (isset($_GET["r_url"])) {
	$r_url = $_GET["r_url"];
} else {
	$r_url = $_SERVER["HTTP_HOST"];
}

// ------ GET MAC|IP ------ 
$ip = $_SERVER['REMOTE_ADDR'];
$mac = getMAC($ip);

// ------ LOGS ------
$current_script = basename(__FILE__);
storeEvent($mac, $ip, $current_script, $r_url);

// ------ NEXT PAGE ------
if ($mod_captive_policy == "1") {
	$next_page = "http://$io_in_ip/captive/$mod_captive_template/policy.php";
} else {
	$next_page = "http://$io_in_ip/captive/$mod_captive_template/welcome.php";
}

setRedirect();
?>
<? print_header($portal_name); ?>

<link rel="stylesheet" href="style.css" />
<h1>Welcome to <?=$mod_captive_portal_name;?></h1>

To access you must first enter your details:<br><br>
<form name="access" action="<?=$next_page?>?r_url=<?=$r_url?>" method='POST' autocomplete="off">
	<div style="background-color:#304E87;width:300px; height:20px;" align="left">&nbsp;&nbsp;<img src="wifi-zone.png" height="34px"></div>
	<div style="background-color:#EFEFEF;width:300px" align="center">
	<table border=0 cellpadding=5 cellspacing=0 width="220px" style="background-color:#EFEFEF">
		<tr>
			<td height="10px"></td>
			<td></td>
		</tr>
		<tr>
			<td nowrap>Your Name:</td>
			<td><input type='text' name='user'></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input class="input" type='text' name='pass'></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' name='next' value='Next >>'></td>
		</tr>
		<tr>
			<td height="10px"></td>
			<td></td>
		</tr>
	</table>
	</div>
</form>

<? if ($_GET["msg"] == "1") echo "<font color='red'>The login details you entered is incorrect.</font><br>"; ?>

<br>
<b>Note:</b> This system and all connected computers are for UNCLASSIFIED material only
<br><br>

<? print_footer(); ?>
