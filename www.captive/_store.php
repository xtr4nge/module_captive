<?

$input = file_get_contents('php://input');
//$object = json_decode($input);
$data = json_decode($input, true);
//$json = $input;
//var_dump($data);

// ----------------------
// JSON
// ----------------------
include "/usr/share/fruitywifi/www/modules/captive/_info_.php";
include "/usr/share/fruitywifi/www/functions.php";

//$json_file = "/tmp/captiveJSON.txt";
//$json_file = "/usr/share/fruitywifi/www/modules/captive/includes/captive_db.json";

function create_db_file() {
	global $json_file;
	
	$exec = "touch $json_file";
	exec_fruitywifi($exec);
	
	$exec = "chmod 666 $json_file";
	exec_fruitywifi($exec);
}

function debugJSON($dataJSON) {
	global $json_file;
	
	$dataJSON["id"] = uniqid();
	$dataJSON["date"] = date("Y-m-d H:i:s");
	
	check_json_entry_exists($dataJSON["userAgent"], $dataJSON["mac"]);
	
	$data = "$dataJSON".PHP_EOL;
	$fp = fopen($json_file, 'a+');
	fwrite($fp, json_encode($dataJSON) . "\n");
}

function remove_json_entry($db_id) {
	global $json_file;
	
	$exec = "sed -i '/$db_id/d' $json_file";
	exec_fruitywifi($exec);
}

function check_json_entry_exists($userAgent, $mac) {
	global $json_file;
	$exec = "cat $json_file | jq '. | select(.userAgent==\"$userAgent\") | select(.mac==\"$mac\") | .id'";
	exec($exec, $output);
	$db_id = trim($output[0]);
	if ($db_id != "") {
		remove_json_entry($db_id);
	}
}

create_db_file();
debugJSON($data);

exit;

// -----------------------------------------------
// THE SQLITE DB WAS REPLACED BY A JSON FILE
// THE BELOW CODE WILL BE REMOVED
// -----------------------------------------------

/*
$myfile = fopen("/tmp/_data.txt", "w") or die("Unable to open file!");
foreach ($data as $key => $v) {
	fwrite($myfile, $key . ":" .$v."\n");
}
fclose($myfile);

$myfile = fopen("/tmp/_object.txt", "w") or die("Unable to open file!");
foreach ($object as $key => $v) {
	fwrite($myfile, $key . ":" .$v."\n");
}
fclose($myfile);

$myfile = fopen("/tmp/_json.txt", "w") or die("Unable to open file!");
fwrite($myfile, $json);
fclose($myfile);
*/

//echo json_encode(json_decode($data["plugins"],true));
echo json_encode($data["ip"]);
//print "{}";

// ----------------------
// SQL LITE
// ----------------------
$db_path = "/usr/share/fruitywifi/www/modules/captive/includes/db/db.sqlite3";
//$db_path = "db/db.sqlite3";

// CREATE DATABASE
function createDB() {
	global $db_path;
	
    // Create (connect to) SQLite database in file
    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                           PDO::ERRMODE_EXCEPTION);
     
    $file_db->exec("CREATE TABLE IF NOT EXISTS details (
                        id INTEGER PRIMARY KEY,
                        remote_addr TEXT, 
                        remote_mac TEXT,
                        user_agent TEXT,
                        time INTEGER)");
    
    $file_db->exec("CREATE TABLE IF NOT EXISTS plugins (
                        id INTEGER PRIMARY KEY,
                        id_details INTEGER,
                        name TEXT, 
                        filename TEXT,
                        description TEXT,
                        version TEXT,
                        time INTEGER)");
    
    $file_db = null;
}

// CHECK IF MAC+UserAgent EXISTS
function searchRecord($p_remote_mac, $p_user_agent) {
	global $db_path;
	
    // Create (connect to) SQLite database in file
    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = " SELECT count(*) as num FROM details WHERE remote_mac = '$p_remote_mac' AND user_agent = '$p_user_agent'; ";   
    $result = $file_db->query($sql);
    $row = $result->fetch(PDO::FETCH_NUM);
    $file_db = null;
	
    if ($row[0] < 1) {
        return true;
    } else {
		return false;
	}

}

// STORE DETAILS
function setDetails($p_remote_addr, $p_remote_mac, $p_user_agent) {
	global $db_path;
	
    // Create (connect to) SQLite database in file
    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $p_time = date('Y-m-d H:i:s');
        
    $sql = "INSERT INTO details 
            (user_agent, remote_addr, remote_mac, time) 
            values 
            ('$p_user_agent', '$p_remote_addr', '$p_remote_mac', '$p_time');";

    $file_db->exec($sql);

	$last_id = $file_db->lastInsertId();
	
    $file_db = null;
	
	return $last_id;
    
}

// STORE PLUGINS
function setPlugins($last_id, $p_name, $p_filename, $p_description, $p_version) {
	global $db_path;
	
    // Create (connect to) SQLite database in file
    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$p_id_details = $last_id;
    $p_time = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO plugins 
            (id_details, name, filename, description, version, time) 
            values 
            ('$p_id_details', '$p_name', '$p_filename', '$p_description', '$p_version', '$p_time');";
    
    $file_db->exec($sql);
	
    $file_db = null;
}

// ACTION
createDB();

// CHECK IF MAC+UserAgent ALREADY EXISTS
if (searchRecord($data["mac"], $data["userAgent"])) {
	
	// INSERT DETAILS AND RETURN LAST_ID 
	$last_id = setDetails($data["ip"], $data["mac"], $data["userAgent"]);
	
	// INSERT PLUGINS
	$plugins = json_decode($data["plugins"],true);
	for ($i=0; $i < count($plugins); $i++) {
		setPlugins($last_id, $plugins[$i]["name"], $plugins[$i]["filename"], $plugins[$i]["description"], $plugins[$i]["version"]);
	}
	
}

?>