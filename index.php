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
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWifi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?

include "_info_.php";
include "../../config/config.php";
include "../../functions.php";

//$bin_danger = "/usr/share/FruityWifi/bin/danger";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_POST["service"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
$tempname = $_GET["tempname"];
$service = $_POST["service"];


// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "rm ".$mod_logs_history.$logfile.".log";
    exec("$bin_danger \"$exec\"", $dump);
}

// SET MODE
if ($_POST["change_mode"] == "1") {
    $ss_mode = $service;
    $exec = "/bin/sed -i 's/ss_mode.*/ss_mode = \\\"".$ss_mode."\\\";/g' includes/options_config.php";
    exec("$bin_danger \"$exec\"", $output);
}

include "includes/options_config.php";

?>

<div class="rounded-top" align="left"> &nbsp; <b>Captive</b> </div>
<div class="rounded-bottom">

    &nbsp;&nbsp;version <?=$mod_version?><br>
    <? 
    if (file_exists($bin_conntrack)) { 
        echo "conntrack <font style='color:lime'>installed</font><br>";
    } else {
        echo "conntrack <font style='color:red'>install</font><br>";
    } 
    ?>
    <? 
    if (file_exists("/var/www/site/captive")) { 
        echo "&nbsp; Captive <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp; Captive <a href='includes/module_action.php?service=install_portal' style='color:red'>install</a><br>";
    } 
    ?>
    
    <?
    $exec = "$bin_iptables -t mangle -L|grep -iEe 'internet.+anywhere'";
    $ismoduleup = exec("$bin_danger \"$exec\"" );
    //$ismoduleup = exec("ps auxww | grep ngrep | grep -v -e 'grep ngrep'");
    if ($ismoduleup != "") {
        echo "&nbsp; Captive  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"includes/module_action.php?service=captive&action=stop&page=module\"><b>stop</b></a>";
    } else { 
        echo "&nbsp; Captive  <font color=\"red\"><b>disabled</b></font>. | <a href=\"includes/module_action.php?service=captive&action=start&page=module\"><b>start</b></a>"; 
    }
    ?>
    
</div>

<br>


<div id="msg" style="font-size:largest;">
Loading, please wait...
</div>

<div id="body" style="display:none;">


    <div id="result" class="module">
        <ul>
            <li><a href="#result-1">Output</a></li>
            <li><a href="#result-2">Users</a></li>
            <li><a href="#result-3">Options</a></li>
            <li><a href="#result-4">History</a></li>
        </ul>
        <div id="result-1">
            <form id="formLogs-Refresh" name="formLogs-Refresh" method="POST" autocomplete="off" action="index.php">
            <input type="submit" value="refresh">
            <br><br>
            <?
                if ($logfile != "" and $action == "view") {
                    $filename = $mod_logs_history.$logfile.".log";
                } else {
                    $filename = $mod_logs;
                }
            
                $data = open_file($filename);
                
                // REVERSE
                //$data_array = explode("\n", $data);
                //$data = implode("\n",array_reverse($data_array));
                
            ?>
            <textarea id="output" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
            <input type="hidden" name="type" value="logs">
            </form>
            
        </div>
        
        <!-- USERS -->
        
        <div id="result-2">
            <form action="index.php" method="GET">
                <input type="hidden" name="tab" value="1">
                <input type="submit" value="refresh">
            </form>
            <br><br>
            <div class="module" style="background-color:#000; border:1px dashed; padding:5px">
            <?
            
            //$filename = "/var/www/site/captive/admin/users";
            $filename = $file_users;
            
            $exec = "$bin_cat $filename";
            exec("$bin_danger \"$exec\"", $output);
            ?>
            
            <table border='0'>
            <tr>
                <td></td>
                <td style="padding-right:10px"><b>Name</b></td>
                <td style="padding-right:10px"><b>Email</b></td>
                <td style="padding-right:10px"><b>IP address</b></td>
                <td style="padding-right:10px"><b>MAC address</b></td>
                <td style="padding-right:10px"><b>Start</b></td>
            </tr>
            <? 
            for ($i=0; $i < count($output); $i++) { 
                $row = explode("|", $output[$i]);
                if ($row[4] != "") {
            ?>
                <tr>
                    <td style="padding-right:5px"><a href="includes/module_action.php?service=users&action=delete&mac=<?=$row[3]?>">Delete</a></td>
                    <td style="background-color:#222; padding-right:10px"><?=$row[0];?></td>
                    <td style="background-color:#222; padding-right:10px"><?=$row[1];?></td>
                    <td style="background-color:#222; padding-right:10px"><?=$row[2];?></td>
                    <td style="background-color:#222; padding-right:10px"><?=$row[3];?></td>
                    <td style="background-color:#222; padding-right:10px"><?=$row[4];?></td>
                </tr>
            <?  } 
            } 
            ?>
            </table>
            </div>
            
            <br>
            
        </div>
        
        <!-- OPTIONS -->

        <div id="result-3">
            <form id="formInject" name="formInject" method="POST" autocomplete="off" action="includes/save.php">
            <input type="submit" value="save">
            <br><br>
            
            <div class="module" style="background-color:#000; border:1px dashed;">
            <table>
                <!-- // OPTION validate Name --> 
                <tr>
                    <? $opt = "name"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($portal[$opt][0] == "1") echo "checked" ?> ></td>
                    <td></td>
                    <td nowrap> Validate Name (javascript)</td>
                </tr>
                <!-- // OPTION Validate Email --> 
                <tr>
                    <? $opt = "email"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($portal[$opt][0] == "1") echo "checked" ?> ></td>
                    <td></td>
                    <td nowrap> Validate Email (javascript)</td>
                </tr>
            </table>
            </div>

            <input type="hidden" name="type" value="portal">
            </form>
            <br>
            <?
                $filename = "$mod_path/includes/mode_d.txt";
                
                $data = open_file($filename);
                
            ?>
            
        </div>

        <!-- HISTORY -->

        <div id="result-4">
            <input type="submit" value="refresh">
            <br><br>
            
            <?
            $logs = glob($mod_logs_history.'*.log');
            print_r($a);

            for ($i = 0; $i < count($logs); $i++) {
                $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=3'><b>x</b></a> ";
                echo $filename . " | ";
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
                echo "<br>";
            }
            ?>
            
        </div>
        
    </div>

    <div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
        Loading...
    </div>

    <script>
    $('#formLogs').submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'includes/ajax.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                console.log(data);

                $('#output').html('');
                $.each(data, function (index, value) {
                    $("#output").append( value ).append("\n");
                });
                
                $('#loading').hide();
            }
        });
        
        $('#output').html('');
        $('#loading').show()

    });

    $('#loading').hide();

    </script>

    <script>
    $('#form1').submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'includes/ajax.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                console.log(data);

                $('#output').html('');
                $.each(data, function (index, value) {
                    if (value != "") {
                        $("#output").append( value ).append("\n");
                    }
                });
                
                $('#loading').hide();

            }
        });
        
        $('#output').html('');
        $('#loading').show()

    });

    $('#loading').hide();

    </script>

    <script>
    $('#formInject2').submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'includes/ajax.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                console.log(data);

                $('#inject').html('');
                $.each(data, function (index, value) {
                    $("#inject").append( value ).append("\n");
                });
                
                $('#loading').hide();
                
            }
        });
        
        $('#output').html('');
        $('#loading').show()

    });

    $('#loading').hide();

    </script>

    <?
    if ($_GET["tab"] == 1) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 1 });";
        echo "</script>";
    } else if ($_GET["tab"] == 2) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 2 });";
        echo "</script>";
    } else if ($_GET["tab"] == 3) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 3 });";
        echo "</script>";
    } else if ($_GET["tab"] == 4) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 4 });";
        echo "</script>";
    } 
    ?>

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#body').show();
    $('#msg').hide();
});
</script>

</body>
</html>
