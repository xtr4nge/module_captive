<? 
/*
    Copyright (C) 2013-2014 xtr4nge [_AT_] gmail.com

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
//include "../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

include "options_config.php";

/*
$bin_danger = "/usr/share/FruityWifi/bin/danger";
$bin_iptables = "/sbin/iptables";
$bin_awk = "/usr/bin/awk";
$bin_grep = "/bin/grep";
$bin_sed = "/bin/sed";
*/

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($io_action, "../msg.php", $regex_extra);
    regex_standard($_GET["mac"], "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$mac =  strtoupper($_GET['mac']);
$install = $_GET['install'];

if($service == "captive") {
    
    if ($action == "start") {
        // START MODULE
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            //exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" ); //DEPRECATED
	    exec_fruitywifi($exec);
            
            $exec = "echo '' > $mod_logs";
            //exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" ); //DEPRECATED
	    exec_fruitywifi($exec);
        }
        
        # Masquerade any incoming packet on the firewall
        $exec = "$bin_iptables -A POSTROUTING -t nat -o $io_out_iface -j MASQUERADE";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        
        # Create a new chain named 'internet' in mangle table with this command
        $exec = "$bin_iptables -t mangle -N internet";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        
        # Send all HTTP traffic from WIFI to the newly created chain for further processing
        $exec = "$bin_iptables -t mangle -A PREROUTING -i $io_action -p tcp -m tcp --dport 80 -j internet";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        $exec = "$bin_iptables -t mangle -A PREROUTING -i $io_action -p tcp -m tcp --dport 443 -j internet";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        
        # Mark all traffic from internet chain with 99
        $exec = "$bin_iptables -t mangle -A internet -j MARK --set-mark 99";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        
        # Redirect all marked traffic to the portal 
        $exec = "$bin_iptables -t nat -A PREROUTING -i $io_action -p tcp -m mark --mark 99 -m tcp --dport 80 -j DNAT --to-destination $io_in_ip";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        $exec = "$bin_iptables -t nat -A PREROUTING -i $io_action -p tcp -m mark --mark 99 -m tcp --dport 443 -j DNAT --to-destination $io_in_ip";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);

        # FORWARD
        $exec = "echo '1' > /proc/sys/net/ipv4/ip_forward";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        
        // INCLUDE INDEX
	if (!file_exists("/var/www/index.php")) {
            $exec = "$bin_echo '.' >> /var/www/index.php";
            exec_fruitywifi($exec);
        }
	
        $exec = "grep 'FruityWifi-Phishing' /var/www/index.php";
        $isphishingup = exec($exec);
        if ($isphishingup  != "") {
            $exec = "sed -i '/FruityWifi-Phishing/d' /var/www/index.php";
            //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	    exec_fruitywifi($exec);
	    
            $exec = "sed -i 1i'<? include \\\"site\/index.php\\\"; \/\* FruityWifi-Phishing \*\/ ?>' /var/www/index.php";
            //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	    exec_fruitywifi($exec);
            
            $exec = "sed -i 1i'<? header(\\\"Location: site\/captive\/index.php\\\"); exit; \/\* FruityWifi-Captive \*\/ ?>' /var/www/index.php";
            //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	    exec_fruitywifi($exec);
	    
        } else {
            $exec = "sed -i 1i'<? header(\\\"Location: site\/captive\/index.php\\\"); exit; \/\* FruityWifi-Captive \*\/ ?>' /var/www/index.php";
            //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	    exec_fruitywifi($exec);
        }

        
    } else if($action == "stop") {
        // STOP MODULE

        // REMOVE INCLUDE
        $exec = "sed -i '/FruityWifi-Captive/d' /var/www/index.php";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);

        # Send all HTTP traffic from WIFI to the newly created chain for further processing
        $exec = "$bin_iptables -t mangle -D PREROUTING -i $io_action -p tcp -m tcp --dport 80 -j internet";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        $exec = "$bin_iptables -t mangle -D PREROUTING -i $io_action -p tcp -m tcp --dport 443 -j internet";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        
        # Mark all traffic from internet chain with 99
        $exec = "$bin_iptables -t mangle -D internet -j MARK --set-mark 99";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        
        # Redirect all marked traffic to the portal 
        $exec = "$bin_iptables -t nat -D PREROUTING -i $io_action -p tcp -m mark --mark 99 -m tcp --dport 80 -j DNAT --to-destination $io_in_ip";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        $exec = "$bin_iptables -t nat -D PREROUTING -i $io_action -p tcp -m mark --mark 99 -m tcp --dport 443 -j DNAT --to-destination $io_in_ip";
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
	exec_fruitywifi($exec);
        
        // DELETE ALLOWED MAC RULES
        $exec = "$bin_iptables -t mangle -L --line-numbers | grep RETURN | $bin_awk '{print $1}'";
        //exec("$bin_danger \"$exec\"",$output); //DEPRECATED
	$output = exec_fruitywifi($exec);

        for ($i=0; $i < count($output); $i++) {
            $exec = "$bin_iptables -t mangle -D internet 1";
            //exec("$bin_danger \"$exec\"",$output); //DEPRECATED
	    $output = exec_fruitywifi($exec);
        }
        
        // CLEAN USERS FILE
        $exec = "echo '-' > $file_users";
        //exec("$bin_danger \"$exec\"",$output); //DEPRECATED
	exec_fruitywifi($exec);
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            //exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" ); //DEPRECATED
	    exec_fruitywifi($exec);
            
            $exec = "echo '' > $mod_logs";
            //exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" ); //DEPRECATED
	    exec_fruitywifi($exec);
        }

    }

}

if($service == "install_portal") {
    $exec = "/bin/ln -s $mod_path/www.captive /var/www/site/captive";
    //exec("$bin_danger \"$exec\""); //DEPRECATED
    exec_fruitywifi($exec);
}

//$filename = "/var/www/site/captive/admin/users";
$filename = $file_users;

if ($service == "users" and $mac != "") {
    $mac = trim($mac,'*');

    if ($action == "delete") {
    
	$exec = "$bin_sed -i '/$mac/d' $filename";
	//exec("$bin_danger \"$exec\"", $output); //DEPRECATED
	exec_fruitywifi($exec);
	
	$exec = "$bin_iptables -D internet -t mangle -m mac --mac-source $mac -j RETURN";
	//exec("$bin_danger \"$exec\""); //DEPRECATED
	exec_fruitywifi($exec);
	
	// ADD TO LOGS
	$exec = "$bin_echo 'DELETE: $mac|".date("Y-m-d h:i:s")."' >> $mod_logs ";
	//exec("$bin_danger \"$exec\""); //DEPRECATED
	exec_fruitywifi($exec);
	
    } 
    
    header('Location: ../index.php?tab=1');
    exit;
}

if ($install == "install_captive") {

    $exec = "$bin_chmod 755 install.sh";
    //exec("$bin_danger \"$exec\"" ); //DEPRECATED
    exec_fruitywifi($exec);

    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    //exec("$bin_danger \"$exec\"" ); //DEPRECATED
    exec_fruitywifi($exec);

    header('Location: ../../install.php?module=captive');
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php');
} else {
    header('Location: ../../action.php?page=captive');
}

//header('Location: ../index.php?tab=0');

?>