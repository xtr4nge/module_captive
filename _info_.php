<?
$mod_name="captive";
$mod_version="1.4";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/captive.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_panel="show";
$mod_isup="/usr/share/fruitywifi/bin/danger \"/sbin/iptables -t mangle -L|grep -iEe 'internet.+anywhere'\"";
$mod_alias="Captive";
# EXEC
$bin_danger = "/usr/share/fruitywifi/bin/danger";
$bin_sudo = "/usr/bin/sudo";
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
