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

include "../../../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

if ($regex == 1) {
    regex_standard($_GET["type"], "../msg.php", $regex_extra);
	regex_standard($_GET["json_id"], "../msg.php", $regex_extra);
}

//$json_file = "/tmp/captiveJSON.txt";
$type = $_GET['type'];
$json_id = $_GET['json_id'];
//$json_id = "57eab7b5126a2";

?>
<style>
	body, td, tr {
		font-family: monospace, curier;
		font-size: 11px;
		background-color: #F8F8F8;
	}
	.db_row {
		padding: 5px;
	}
	.db_title {
		font-weight: bold;
	}
</style>
<?

$exec = "grep $json_id $json_file";
exec($exec, $output);

$object = [];

$line = $output[0];
unset($output);

if ($line != "") {
	$data = str_replace("\n","",$line);
	$data = json_decode($data);
	$temp_details = [];
	foreach($data as $key=>$value) {
		if ($key == "plugins") {
			$plugins = json_decode($value, true);
			$temp_plugins = [];
			for ($i=0; $i < count($plugins); $i++) {
				$temp_plugins[] = $plugins[$i];
			}
			$temp_details[$key] = $temp_plugins;
		} else {
			$temp_details[$key] = $value;
		}
	}
	$output[] = $temp_details;
}

//print_r($output);

function showDetails($output) {
	$v_id = $output[0]["id"];
	$v_date = $output[0]["date"];
	$v_ip = $output[0]["ip"];
	$v_mac = $output[0]["mac"];
	$v_platform = $output[0]["platform"];
	$v_user_agent = $output[0]["userAgent"];
	$v_app_code_name = $output[0]["appCodeName"];
	$v_app_name = $output[0]["appName"];
	$v_language = $output[0]["language"];
	
	echo "
			<table>
				<tr>
					<td class='db_title'>date</td>
					<td class='db_row'>$v_date</td>
				</tr>
				<tr>
					<td class='db_title'>ip</td>
					<td class='db_row'>$v_ip</td>
				</tr>
				<tr>
					<td class='db_title'>macaddress</td>
					<td class='db_row'>$v_mac</td>
				</tr>
				<tr>
					<td valign='top' class='db_title'>user-agent</td>
					<td valign='top' class='db_row'>$v_user_agent</td>
				</tr>
				<tr>
					<td class='db_title'>Platform</td>
					<td class='db_row'>$v_platform</td>
				</tr>
				<tr>
					<td class='db_title'>language</td>
					<td class='db_row'>$v_language</td>
				</tr>
				<tr>
					<td class='db_title'>appCodeName</td>
					<td class='db_row'>$v_app_code_name</td>
				</tr>
				<tr>
					<td class='db_title'>appName</td>
					<td class='db_row'>$v_app_name</td>
				</tr>
			</table>
	";
	
}

function showPlugins($output) {
	echo "
		<table>
			<tr>
				<td class='db_title'>name</td>
				<td class='db_title'>filename</td>
				<td class='db_title'>description</td>
			</tr>
		";
	$plugins = $output[0]["plugins"];
	for ($i=0; $i < count($plugins); $i++) {
		$v_name = $plugins[$i]["name"];
		$v_filename = $plugins[$i]["filename"];
		$v_description = $plugins[$i]["description"];
		
		echo "
			<tr>
				<td valign='top' class='db_row'>$v_name</td>
				<td valign='top' class='db_row'>$v_filename</td>
				<td valign='top' class='db_row'>$v_description</td>
			</tr>
		";
	}
	echo "</table>";
}



if ($type == "details") {
	showDetails($output);
} else if ($type == "plugins") {
	showPlugins($output);	
} else {
	echo "nothing to show...";
}

?>