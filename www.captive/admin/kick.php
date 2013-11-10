<? 
/*
	Copyright (C) 2013  xtr4nge [_AT_] gmail.com

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

$exec_root = "/usr/share/FruityWifi/bin/danger";
//exec("$exec_root \"$exec\"");

if (!isset($_GET["ip"])) {
    echo "ip?";
    exit;
}

// get the user IP address from the query string
$ip = $_GET['ip'];

// this is the path to the arp command used to get user MAC address 
// from it's IP address in linux environment.
$arp = "/usr/sbin/arp";

// execute the arp command to get their mac address
$exec = "$arp -an " . $ip;
//$mac = shell_exec("sudo $arp -an " . $ip);
$mac = exec("$exec_root \"$exec\"");

preg_match('/..:..:..:..:..:../',$mac , $matches);
$mac = @$matches[0];

// if MAC Address couldn't be identified.
if( $mac === NULL) {
  echo "Error: Can't retrieve user's MAC address.";
  exit;
}

// Delete it from iptables bypassing rules entry.
$exec = "/sbin/iptables -t mangle -L | grep ".strtoupper($mac);
while( $chain = shell_exec("$exec_root \"$exec\"") !== NULL ) {
    $exec = "/sbin/iptables -D internet -t mangle -m mac --mac-source ".strtoupper($mac)." -j RETURN";
    exec("$exec_root \"$exec\"");
}
// Why in this while loop?
// Users may have been logged through the portal several times. 
// So they may have chances to have multiple bypassing rules entry in iptables firewall.

// remove their connection track.
$exec = "./rmtrack " . $ip;
exec("$exec_root \"$exec\"");
// remove their connection track if any

echo "Kickin' successful.";
?>
