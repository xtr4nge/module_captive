<? 
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
<?
include "/usr/share/fruitywifi/www/config/config.php";
include "/usr/share/fruitywifi/www/modules/captive/_info_.php";
include "/usr/share/fruitywifi/www/functions.php";
include "../_portal_functions.php";

// ------ CHECK LOGIN ------ 
$r_url = $_GET["r_url"];

$user = htmlentities($_POST["user"]);
$pass = htmlentities($_POST["pass"]);

if ($mod_captive_validate_user == "1" and $mod_captive_portal_user != $user) {
    header("Location: register.php?r_url=$r_url&msg=1");
    exit;
}

if ($mod_captive_validate_pass == "1" and $mod_captive_portal_pass != $pass) {
    header("Location: register.php?r_url=$r_url&msg=1");
    exit;
}

// ------ GET MAC|IP ------ 
$ip = $_SERVER['REMOTE_ADDR'];
$mac = getMAC($ip);

// ------ LOGS ------
$current_script = basename(__FILE__);
storeEvent($mac, $ip, $current_script, $r_url);

// ------ NEXT PAGE ------
$next_page = "http://$io_in_ip/captive/$mod_captive_template/welcome.php?r_url=$r_url";

// if MAC Address couldn't be identified.
if( $mac === NULL) { 
  echo "Access Denied.";
  exit;
}
?>
<? print_header($portal_name); ?>

<link rel="stylesheet" href="style.css" />

    <h1>Welcome to <?php echo $portal_name;?></h1>
    You must agree to the Acceptable Use Policy:<br><br>
    
    <form action="<?=$next_page?>" method="POST">
        <table border=0 cellpadding=5 cellspacing=0 width="100%">
            <tr>
                <td><textarea name='aup' readonly rows='10' style="width:100%;"><?php echo $aup; ?></textarea></td>
            </tr>
            <tr>
                <td align=center height='23'><input type='submit' name='agree' value='I agree to the Acceptable Use Policy'></td>
            </tr>
        </table>
        <input type="hidden" name="mac" value="<?php echo $mac; ?>" />
        <input type="hidden" name="ip" value="<?php echo $ip; ?>" />
        <input type="hidden" name="user" value="<?php echo $user; ?>" />
        <input type="hidden" name="pass" value="<?php echo $pass; ?>" />
    </form>

<? print_footer(); ?>