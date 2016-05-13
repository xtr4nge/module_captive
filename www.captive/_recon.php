<!DOCTYPE html>
<style>
	body {
		font-family: monospace;
		
	}
</style>
<?
include "/usr/share/fruitywifi/www/functions.php";

function getIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function getMAC($ip) {
	// capture their IP address
	//$ip = $_SERVER['REMOTE_ADDR'];
	
	// this is the path to the arp command used to get user MAC address 
	// from it's IP address in linux environment.
	$arp = "/usr/sbin/arp";
	
	// execute the arp command to get their mac address
	$exec = "$arp -an " . $ip;

	//$mac = shell_exec("sudo $arp -an " . $ip);
	//$mac = exec_fruitywifi($exec);
	exec($exec, $mac);
		
	preg_match('/..:..:..:..:..:../',$mac[0] , $matches);
	$mac = @$matches[0];
	
	return $mac;
}

function getMacAddress($p_ip) {
    $path = "/usr/share/fruitywifi/logs/dhcp.leases";
    
    $exec = "grep '$p_ip' $path | awk {'print $2'}";
    exec($exec, $output);

    return $output[0];
}

echo "<br><br>";
$v_ip =  getIP();
echo $v_ip;
echo "<br>";
$v_mac = getMAC($v_ip);
echo $v_mac;

echo "<br>";
echo "<br>";

?>

<script src='jquery.js'></script>
<div id="navigator"></div>
<script>
	
function serialize (object) {
	var type = typeof object;
	if (object === null) {
		return '"nullValue"';
	}
	if (type == 'string' || type === 'number' || type === 'boolean') {
		return '"' + object + '"';
	}
	else if (type === 'function') {
		return '"functionValue"';
	}
	else if (type === 'object') {
		var output = '{';
		for (var item in object) {
			if (item !== 'enabledPlugin') {
				output += '"' + item + '":' + serialize(object[item]) + ',';
			}
		}
		return output.replace(/\,$/, '') + '}';
	}
	else if (type === 'undefined') {
		return '"undefinedError"';
	}
	else {
		return '"unknownTypeError"';
	}
};
$(document).ready(function () {
	$('#navigator').text(serialize(navigator));
});

//console.log(JSON.parse(serialize(navigator)))
			
	
function getPlugins(id_details) {
	
	console.log(navigator)
	
	data = {}
	//var navigatorString;
	for(obj in navigator){
		//navigatorString+=obj+':'+JSON.stringify(navigator[obj]);
		//data[obj] = JSON.stringify(navigator[obj]);
	}
	
	//data = navigatorString
	data.appCodeName = navigator.appCodeName
	data.appName = navigator.appName
	data.userAgent = navigator.userAgent
	data.platform = navigator.platform
	data.cookieEnabled = navigator.cookieEnabled
	data.language = navigator.language
	data.ip = "xx.xx.xx.xx";
	data.mac = "00:00:00:00:00";
	
	console.log(data)
	//console.log(JSON.stringify(data,null,2))
	
	test = navigator
	test.ip = "xx.xx.xx.xx";
	test.mac = "00:00:00:00:00";
	console.log(test)
	
	
	//OTHER
	console.log(navigator.product)
	console.log(navigator.appVersion)
	//PLUGINS
	var server = location.host;
	
	//document.write(id_details);
	
	var L = navigator.plugins.length;
	
	document.write(
	  L.toString() + " Plugin(s)<br>" + "Name | Filename | description<br>"
	);
	
	p_time = "";
	
	for(var i = 0; i < L; i++) {
	  //sendPlugins(id_details, navigator.plugins[i].name, navigator.plugins[i].filename, navigator.plugins[i].description, navigator.plugins[i].version, p_time);
	  console.log(navigator.plugins[i])
	  document.write(
		navigator.plugins[i].name +
		" | " +
		navigator.plugins[i].filename +
		" | " +
		navigator.plugins[i].description +
		" | " +
		navigator.plugins[i].version +
		"<br>"
	  );
	}
}

//getPlugins("test")

function getData(v_ip, v_mac) {
	/*
	data = navigator
	data.ip = v_ip;
	data.mac = v_mac;
	console.log(data)
	*/
	
	// SET PLUGINS
	plugins = {}
	for(var i = 0; i < navigator.plugins.length; i++) {
		plugins[i] = {	"name":navigator.plugins[i].name,
						"filename":navigator.plugins[i].filename,
						"description":navigator.plugins[i].description,
						"version":navigator.plugins[i].version
					}
	  document.write(
		navigator.plugins[i].name +
		" | " +
		navigator.plugins[i].filename +
		" | " +
		navigator.plugins[i].description +
		" | " +
		navigator.plugins[i].version +
		"<br>"
	  );
	}
	
	// SET DATA
	data = {}
	data.appCodeName = navigator.appCodeName
	data.appName = navigator.appName
	data.userAgent = navigator.userAgent
	data.platform = navigator.platform
	data.cookieEnabled = navigator.cookieEnabled
	data.language = navigator.language
	data.plugins = JSON.stringify(plugins)
	data.ip = v_ip;
	data.mac = v_mac;
	
	console.log(JSON.parse(JSON.stringify(data)))
	
	// SEND JSON to PHP
	$.ajax({
		type: 'POST',
		//async: false,
		url: '_store.php',
		contentType: 'application/json',
		//data: serialize(data),
		data: JSON.stringify(data),
		//data: JSON.parse(JSON.stringify(data)),
		dataType: 'json'
	})
	.done( function( data ) {
		console.log('done');
		console.log(data);
	})
	.fail( function( data ) {
		console.log('fail');
		console.log(data);
	});
	
}

getData('<?=$v_ip?>', '<?=$v_mac?>');

</script>
