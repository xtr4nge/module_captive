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
include "../../login_check.php";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWiFi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script src="includes/scripts.js?4"></script>

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
include "../../config/config.php";
include "_info_.php";
include "../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_POST["service"], "msg.php", $regex_extra);
    //regex_standard($_GET["tempname"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
//$tempname = $_GET["tempname"];
$service = $_POST["service"];


// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "rm ".$mod_logs_history.$logfile.".log";
    exec_fruitywifi($exec);
}

// SET MODE
if ($_POST["change_mode"] == "1") {
    $ss_mode = $service;
    $exec = "/bin/sed -i 's/ss_mode.*/ss_mode = \\\"".$ss_mode."\\\";/g' includes/options_config.php";
    exec_fruitywifi($exec);
}

include "includes/options_config.php";

?>

<div class="rounded-top" align="left"> &nbsp; <b>Captive</b> </div>
<div class="rounded-bottom">

    &nbsp;&nbsp;version <?=$mod_version?><br>
    <?
    /*
    if (file_exists($bin_conntrack)) { 
        echo "conntrack <font style='color:lime'>installed</font><br>";
    } else {
        //echo "conntrack <font style='color:red'>install</font><br>";
    echo "conntrack <a href='includes/module_action.php?install=install_captive' style='color:red'>install</a><br>";
    }
    */
    ?>
    <? 
    //if (file_exists("/var/www/site/captive")) {
    if (file_exists("/var/www/captive")) { 
        echo "&nbsp; Captive <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp; Captive <a href='includes/module_action.php?service=install_portal' style='color:red'>install</a><br>";
    } 
    ?>
    
    <?
    if ($mod_captive_block == "ALL") {
        $exec = "$bin_iptables -t mangle -L|grep -iEe 'internet.+anywhere'";
        $ismoduleup = exec_fruitywifi($exec);
        $ismoduleup = $ismoduleup[0];
        //$ismoduleup = exec("ps auxww | grep ngrep | grep -v -e 'grep ngrep'");
    } else if ($mod_captive_block == "80") {
        $exec = "grep -iEe '^iptables -t nat -A PREROUTING' /usr/share/fruitywifi/conf/dnsmasq-dhcp-script.sh";
        $ismoduleup = exec($exec);
    }
    $ismoduleup = exec($mod_isup);
    if ($ismoduleup != "") {
        echo "&nbsp; Captive  <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='includes/module_action.php?service=captive&action=stop&page=module'><b>stop</b></a>";
    } else { 
        echo "&nbsp; Captive  <font color='red'><b>disabled</b></font>. | <a href='includes/module_action.php?service=captive&action=start&page=module'><b>start</b></a>"; 
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
            <li><a href="#tab-output">Output</a></li>
            <li><a href="#tab-users">Users</a></li>
            <li><a href="#tab-options">Options</a></li>
            <li><a href="#tab-inject">Inject</a></li>
            <li><a href="#tab-db">DB</a></li>
            <li><a href="#tab-history">History</a></li>
        </ul>
        <div id="tab-output">
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
        <!-- END OUTPUT -->
        
        <!-- USERS -->
        <div id="tab-users" class="history">
            
            <form action="?tab=1" method="GET">
                <input type="hidden" name="tab" value="1">
                <input type="submit" value="refresh">
            </form>
            
            <?
            // COPIED FROM AP MODULE ws.php (I need to pull this together...)    
            $data = open_file("/usr/share/fruitywifi/logs/dhcp.leases");
            $out = explode("\n", $data);
            
            $leases = [];
            
            for ($i=0; $i < count($out); $i++) {
                $temp = explode(" ", $out[$i]);
                $leases[$temp[1]] = array($temp[2], $temp[3]);
            }
            
            unset($out);
            unset($data);
            
            $exec = "iw dev $io_in_iface station dump | sed -e 's/^\\t/|/g' | tr '\\n' ' ' | sed -e 's/Sta/\\nSta/g' | tr '\\t' ' '";
            $out = exec_fruitywifi($exec);
            
            $output = [];
            
            for ($i=0; $i < count($out); $i++) {
                
                $station = [];
                
                $temp = explode("|", $out[$i]);
                
                if ($temp[0] != "")
                {
                    foreach ($temp as &$value) {
                        unset($sub);
                        
                        if (strpos($value,'Station ') !== false) {
                            $value = str_replace("Station ","",$value);
                            $value = explode(" ", $value);
                            $mac = $value[0];
                            $value = "station: " . $value[0];
                            $key_mac = $value[0];
                        }
                        
                        $sub = explode(": ",$value);
                        $station[$sub[0]] = $sub[1];
                        
                    }
                    
                    if (array_key_exists($mac, $leases)) {
                        $station["ip"] = $leases[$mac][0];
                        $station["hostname"] = $leases[$mac][1];
                    } else {
                        $station["ip"] = "";
                        $station["hostname"] = "";
                    }
                    $output[] = $station;
                }
            }
            // echo json_encode($output); // FULL OUTPUT
            
            $exec = "$bin_iptables -t nat -L | grep -iEe 'MARK.+MAC' | awk '{print \\\$7}'";
			$output_captive = exec_fruitywifi($exec);
			
            echo "<br>";
            
            /*
			for ($i=0; $i < sizeof($output_captive); $i++) {
				$mac = $output_captive[$i];
                echo "[x] .$mac. <br>";
				//$exec = "$bin_iptables -t nat -D PREROUTING -p tcp -m mac --mac-source $mac -j MARK --set-mark 99";
				//exec_fruitywifi($exec);
			}
            */
            
            for ($i=0; $i < sizeof($output); $i++) {
                $mac = $output[$i]["station"];
                if (in_array(strtoupper($output[$i]["station"]), $output_captive)) {
                    $font_color = "green";
                    $station_action = "<a href='includes/module_action.php?service=station&action=deny&mac=$mac' style='font-family:monospace'>[-]</a>";
                } else {
                    $font_color = "black";
                    $station_action = "<a href='includes/module_action.php?service=station&action=allow&mac=$mac' style='font-family:monospace'>[+]</a>";
                }
                
                
                echo "<div style='color:$font_color; font-family:monospace'> $station_action ";
                echo $output[$i]["station"];
                echo " | ";
                echo $output[$i]["ip"];
                echo " | ";
                echo $output[$i]["hostname"];
                echo "</div>";
            }
            ?>
                        
        </div>
        <!-- END USERS -->
        
        <!-- OPTIONS -->
        <div id="tab-options" class="history">
            
            <h4>
                Captive Portal Options
            </h4>
            <h5>
                <input id="captive_site" type="checkbox" name="my-checkbox" <? if ($mod_captive_site == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_site')" > Portal URL
                <br>
                <input id="captive_site_value" class="form-control input-sm" placeholder="URL" value="<?=$mod_captive_site_value?>" style="width: 200px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="submit" value="save" onclick="setOption('captive_site_value', 'mod_captive_site_value')">
                
                <br><br>
                
                Portal Name
                <br>
                <input id="captive_portal_name" class="form-control input-sm" placeholder="Portal Name" value="<?=$mod_captive_portal_name?>" style="width: 200px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="submit" value="save" onclick="setOption('captive_portal_name', 'mod_captive_portal_name')">
                
                <br><br>
                <input id="captive_policy" type="checkbox" name="my-checkbox" <? if ($mod_captive_policy == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_policy')" > Show Policy Page
                <br>
                <input id="captive_welcome" type="checkbox" name="my-checkbox" <? if ($mod_captive_welcome == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_welcome')" > Show Welcome Page
                                
                <br><br>
                <input id="captive_recon" type="checkbox" name="my-checkbox" <? if ($mod_captive_recon == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_recon')" > Recon (inject recon)
                <br>
                <input id="captive_inject" type="checkbox" name="my-checkbox" <? if ($mod_captive_inject == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_inject')" > Inject (inject code)
                
                <br><br>
                <input id="captive_validate_user" type="checkbox" name="my-checkbox" <? if ($mod_captive_validate_user == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_validate_user')" > Validate User/Email
                <br>
                <input id="captive_validate_pass" type="checkbox" name="my-checkbox" <? if ($mod_captive_validate_pass == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_validate_pass')" > Validate Pass

                <br><br>
                <div style="width: 30px; display: inline-block">User</div> <input id="captive_portal_user" class="form-control input-sm" placeholder="User/Email" value="<?=$mod_captive_portal_user?>" style="width: 120px; display: inline-block; " type="text" />
                <br>
                <div style="width: 30px; display: inline-block">Pass</div> <input id="captive_portal_pass" class="form-control input-sm" placeholder="Password" value="<?=$mod_captive_portal_pass?>" style="width: 120px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('captive_portal_user', 'mod_captive_portal_user'); setOption('captive_portal_pass', 'mod_captive_portal_pass')">
            </h5>
            
            <br>
            
            <h4>Captive Portal Template</h4>
            <h5>
                <?
                $portal_folder = glob($mod_path.'/www.captive/portal_*');
                //print_r($portal_folder);
                
                foreach ($portal_folder as $value) {
                    $value = str_replace("$mod_path/www.captive/","",$value);
                ?>
                    <input id="captive_template_<?=$value?>" value="<?=$value?>" type="radio" name="my-radio" <? if ($mod_captive_template == $value) echo "checked"; ?> onclick="setRadio(this, 'mod_captive_template')" > <?=$value?>
                    <br>
                <? } ?>
            </h5>
            
            <br>
            
            <h4>Redirect</h4>
            <h5>
                <input id="captive_redirect_change_http" type="checkbox" name="my-checkbox" <? if ($mod_captive_redirect_change_http == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_redirect_change_http')" > http2https
                <br>
                <input id="captive_redirect_change_www" type="checkbox" name="my-checkbox" <? if ($mod_captive_redirect_change_www == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_redirect_change_www')" > Add | Remove www (oposite)
                <br>
                <input id="captive_redirect_timestamp" type="checkbox" name="my-checkbox" <? if ($mod_captive_redirect_timestamp == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_redirect_timestamp')" > Timestamp (add)
                
                <br><br>
                
                <input id="captive_redirect" type="checkbox" name="my-checkbox" <? if ($mod_captive_redirect == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_captive_redirect')" > Force Redirect
                <br>
                <input id="captive_redirect_value" class="form-control input-sm" placeholder="Redirect" value="<?=$mod_captive_redirect_value?>" style="width: 200px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="submit" value="save" onclick="setOption('captive_redirect_value', 'mod_captive_redirect_value')">
            </h5>
            
        </div>
        <!-- END OPTIONS -->
        
        <!-- INJECT -->
        <div id="tab-inject" >
            <form id="formInject" name="formInject" method="POST" autocomplete="off" action="includes/save.php">
            <input type="submit" value="save">
            <br><br>
            <?
                $filename = "$mod_path/includes/inject.txt";
                
                /*
                if ( 0 < filesize( $filename ) ) {
                    $fh = fopen($filename, "r"); // or die("Could not open file.");
                    $data = fread($fh, filesize($filename)); // or die("Could not read file.");
                    fclose($fh);
                }
                */
                
                $data = open_file($filename);
                
            ?>
            <textarea id="inject" name="newdata" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
            <input type="hidden" name="type" value="inject">
            </form>
        </div>
        <!-- END INJECT -->
        
        <!-- DB -->
        
        <div id="tab-db">
            <form id="formLogs-Refresh" name="formLogs-Refresh" method="POST" autocomplete="off" action="index.php?tab=4">
                <input type="submit" value="refresh">
            </form>
            <br>
            <iframe class="module-options" style="width:97%; height:200px; background-color: #EEE" border=0 src="includes/output.php"></iframe>
            
        </div>
        
        <!-- END DB -->
        
        <!-- HISTORY -->

        <div id="tab-history" class="history">
            <input type="submit" value="refresh">
            <br><br>
            
            <?
            $logs = glob($mod_logs_history.'*.log');
            //print_r($a);

            for ($i = 0; $i < count($logs); $i++) {
                $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=5'><b>x</b></a> ";
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
    } else if ($_GET["tab"] == 5) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 5 });";
        echo "</script>";
    } 
    ?>

</div>

<script type="text/javascript">
    $('#loading').hide();
    $(document).ready(function() {
        $('#body').show();
        $('#msg').hide();
    });
</script>

<script>
    $('.btn-default').on('click', function(){
        $(this).addClass('active').siblings('.btn').removeClass('active');
        param = ($(this).find('input').attr('name'));
        value = ($(this).find('input').attr('id'));
        $.getJSON('../api/includes/ws_action.php?api=/config/module/captive/'+param+'/'+value, function(data) {});
    }); 
</script>

</body>
</html>
