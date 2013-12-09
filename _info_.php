<?
$mod_name="captive";
$mod_version="1.1";
$mod_logs="/usr/share/FruityWifi/logs/captive.log"; 
$mod_logs_history="/usr/share/FruityWifi/www/modules/captive/includes/logs/";
$mod_path="/usr/share/FruityWifi/www/modules/captive";
$mod_panel="show";
$mod_isup="/usr/share/FruityWifi/bin/danger \"/sbin/iptables -t mangle -L|grep -iEe 'internet.+anywhere'\"";
$mod_alias="Captive";
# EXEC
$bin_danger = "/usr/share/FruityWifi/bin/danger";
$bin_iptables = "/sbin/iptables";
$bin_awk = "/usr/bin/awk";
$bin_grep = "/bin/grep";
$bin_sed = "/bin/sed";
$bin_conntrack = "/usr/sbin/conntrack";
$bin_cat = "/bin/cat";
$bin_echo = "/bin/echo";
$bin_ln = "/bin/ln";
# FILE
$file_users = "/var/www/site/captive/admin/users";
?>
