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

<? print_header() ?>
<? $site_name = "Free Wifi Zone"; ?>
<h1>Welcome to <?php echo $site_name;?></h1>
<div style="background-color:#304E87;width:300px; height:20px;" align="left">&nbsp;&nbsp;<img src="includes/wifi-zone.png" height="34px"></div>
<div style="background-color:#EFEFEF; width:300px; height:100px" align="center">
<br><br>
Enjoy!
<br>

</div>

<script type="text/javascript">
function delayer(){
    //window.location = "<?=$_GET['site']?>"
}

//setTimeout('delayer()', 4000)

</script>
</head>

<? print_footer(); ?>

<?
// Function to print page header
function print_header() {

  ?>
    <html>
    <head><title>Welcome to <?php echo $site_name;?></title>
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width /">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <LINK rel="stylesheet" type="text/css" href="includes/style.css">
    </head>

  <body bgcolor=#FFFFFF text=000000>
  <?php
}

// Function to print page footer
function print_footer() {
  echo "</body>";
  echo "</html>";

}
?>