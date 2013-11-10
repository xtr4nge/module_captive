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
session_start();
?>

<LINK rel="stylesheet" type="text/css" href="../includes/style.css">

<?php

$exec_root = "/usr/share/FruityWifi/bin/danger";
//exec("$exec_root \"$exec\"");


if (isset($_POST['password'])) {
	if ($_POST['password'] == "admin") {
		$_SESSION['password'] = "admin";
	}
}

if (!isset($_SESSION['password'])) {
	echo "Please enter password:";
	echo "<form method='post'><input type='password' name='password'>
		<input type='submit' value='Logon'></form>";
	echo "</body></html>";
	exit;
}

// validate any inputs
if (isset($_GET['mac'])) {
	if (preg_match('/^..:..:..:..:..:..\*?$/',$_GET['mac'])) {
		$mac = $_GET['mac'];
	} else {
		echo "<font color='red'>Invalid MAC address</font><br><br>";
	}
}

$userfile = "./users";

$mac =  strtoupper($mac);

if (isset($_GET['action']) && $mac) {
	$mac = trim($mac,'*');
    
	if ($_GET['action']=="block") {
		$users = file_get_contents($userfile);
		if (!strpos($users,"$mac*")) {
			$users = str_replace($mac,$mac."*",$users);
			file_put_contents($userfile,$users,LOCK_EX);
		}
		
        //$exec = "/sbin/iptables -D internet -t nat -m mac --mac-source $mac -j RETURN";
        $exec = "/sbin/iptables -D internet -t mangle -m mac --mac-source ".strtoupper($mac)." -j RETURN";
        exec("$exec_root \"$exec\"");
        
	} elseif ($_GET['action']=="unblock") {
		$users = file_get_contents($userfile);
		if (strpos($users,"$mac*")) {
			$users = str_replace($mac."*",$mac,$users);
			file_put_contents($userfile,$users,LOCK_EX);
			
            //$exec = "/sbin/iptables -I internet 1 -t nat -m mac --mac-source $mac -j RETURN";
            $exec = "/sbin/iptables -I internet -t mangle -m mac --mac-source ".strtoupper($mac)." -j RETURN";
            exec("$exec_root \"$exec\"");
		}
	} elseif ($_GET['action']=="delete") {
		$users = file_get_contents($userfile);
		$users = preg_replace("/\n.+$mac.*\n/","\n",$users);
		file_put_contents($userfile,$users,LOCK_EX);
		
        //$exec = "/sbin/iptables -D internet -t nat -m mac --mac-source $mac -j RETURN";
        $exec = "/sbin/iptables -D internet -t mangle -m mac --mac-source ".strtoupper($mac)." -j RETURN";
        exec("$exec_root \"$exec\"");
        //echo $exec;
        
	} else {
		echo "<font color='red'>Invalid action requested</font><br><br>";
	}
}

$users = file("$userfile");


echo "<a href='".$_SERVER['PHP_SELF']."'>Refresh page</center></a>";
echo "<h2>Users</h2>";
echo "<table border='1'>";
echo "<tr>
	<td></td>
	<td><b></b></td>
	<td><b>Name</b></td>
	<td><b>Email</b></td>
	<td><b>Cabin</b></td>
	<td><b>IP address*</b></td>
	<td><b>MAC address</b></td>
	<td><b>Start</b></td>
	</tr>";

$count = 0;
$users = array_reverse($users);
$self = $_SERVER['PHP_SELF'];

foreach ($users as $v) {
	$v = trim($v);
	//$fields = split("\t",$v,7);
	//$fields = explode("\t",$v,7);
    $fields = explode("|",$v,7);
	if (!isset($fields[5])) { $fields[5] = "&nbsp;"; }
	echo "<tr>";
	echo "<td><a href='$self?action=delete&mac=".$fields[4]."'>Delete</a>\n";
	if (strpos($fields[4],"*")) {
		$fields[4] = trim($fields[4],'*');
		echo "<td><a href='$self?action=unblock&mac=".$fields[4]."'>Unblock</a>\n";
		$blocked = "red";
	} else {
		echo "<td><a href='$self?action=block&mac=".$fields[4]."'>Block</a>\n";
		$blocked = "black";
	}
	for ($i=0; $i<=5; $i++) {
		echo "<td><font color='$blocked'>".$fields[$i]."</font></td>\n";
	}
	echo "</tr>";
	$count++;
}

echo "</table>";

?>
