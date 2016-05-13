<?php
include "/usr/share/fruitywifi/www/modules/captive/_info_.php";

if ($mod_captive_site == "1") {
    $redirect = "http://$mod_captive_site_value/captive/welcome.php";
} else {
    $redirect = "welcome.php";
}

header("Location: $redirect");
exit;
?>