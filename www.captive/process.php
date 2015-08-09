<? 
/*
    Copyright (C) 2013-2015 xtr4nge [_AT_] gmail.com

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
//include "/usr/share/fruitywifi/www/login_check.php";
include "/usr/share/fruitywifi/www/functions.php";

if( isset( $_POST['ip'] ) && isset( $_POST['mac'] ) ) {
    $ip = htmlentities($_POST['ip']);
    $mac = strtoupper(htmlentities($_POST['mac']));
    $name = htmlentities($_POST["name"]);
    $email = htmlentities($_POST["email"]);

    $exec = "$bin_iptables -I internet 1 -t mangle -m mac --mac-source $mac -j RETURN";
    exec_fruitywifi($exec);
 
    $exec = "includes/rmtrack " . $ip;
    exec_fruitywifi($exec);
    sleep(1); // allowing rmtrack to be executed
    
    // OK, redirection bypassed.
    // Show the logged in message or directly redirect to other website

    // STORE USER
    $exec = "$bin_echo '$name|$email|$ip|$mac|".date("Y-m-d h:i:s")."' >> $file_users ";
    exec_fruitywifi($exec);
    
    // ADD TO LOGS
    $exec = "$bin_echo 'NEW: $name|$email|$ip|$mac|".date("Y-m-d h:i:s")."' >> $mod_logs ";
    exec_fruitywifi($exec);

    //print_r($_SERVER);
    //exit;

    //header('Location: ' . $_SERVER["HTTP_ORIGIN"]);
    //header('Location: http://10.0.0.1/site/captive/welcome.php?site='.$_SERVER["HTTP_ORIGIN"]);
    //header("Location: http://$io_in_ip/site/captive/welcome.php");
    header("Location: http://$io_in_ip/captive/welcome.php");
    //echo "User logged in.";
    exit;

} else {
    echo "Access Denied"; 
    exit;
}
?>