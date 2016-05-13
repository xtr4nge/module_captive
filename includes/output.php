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
//include "../../../login_check.php";
//include "../../../config/config.php";
//include "../../../functions.php";

$root_path = "/usr/share/fruitywifi";
$root_web = "";
$db_path = "$root_path/www/modules/captive/includes/db/db.sqlite3";

include "$root_path/www/login_check.php";
include "$root_path/www/config/config.php";
include "$root_path/www/functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["type"], "../../../msg.php", $regex_extra);
    regex_standard($_GET["id_details"], "../../../msg.php", $regex_extra);
}

?>

<link rel="stylesheet" href="<?=$root_web?>/modules/css/jquery-ui.css" />
<link rel="stylesheet" href="<?=$root_web?>/modules/css/style.css" />
<link rel="stylesheet" href="<?=$root_web?>/style.css" />

<?

function getDetails() {
    global $db_path;
    // Create (connect to) SQLite database in file

    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    //$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT * FROM details ORDER BY time DESC;";
    
    $result = $file_db->query($sql);
    
    //print_r($result);
    
    $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
    
    echo "<table class='general'>";
    echo "  <tr>
                <td><b>time</b></td>
                <td><b>remote_addr</b></td>
                <td><b>remote_mac</b></td>
                <td><b>plugins</b></td>
            </tr>";
    foreach($rowarray as $row) {
        $p_id = $row["id"];
        $p_remote_addr = $row["remote_addr"]; // user_agent, remote_addr, remote_mac, time
        $p_remote_mac = $row["remote_mac"];
        $p_user_agent = $row["user_agent"];
        $p_time = $row["time"];
        echo "<tr>
            <td style='b-ackground-color:#DDD; padding-right:10px' nowrap>$p_time</td>
            <td style='b-ackground-color:#DDD; padding-right:10px' nowrap>$p_remote_addr</td>
            <td style='b-ackground-color:#DDD; padding-right:10px'><a href='output.php?type=getclientdetails&id_details=$p_id'>$p_remote_mac</a></td>
            <td style='b-ackground-color:#DDD; padding-right:10px'><a href='output.php?type=getplugins&id_details=$p_id'>plugins</a></td>
        </tr>";
    }
    echo "</table>";
    
    $file_db = null;
}

function getClientDetails($id_details) {
    global $db_path;
    
    // Create (connect to) SQLite database in file
    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    //$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT * FROM details WHERE id = '$id_details';";
    
    $result = $file_db->query($sql);
    
    //print_r($result);
    
    $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
    
    
    echo "<table class='general'>";
    /*
    echo "  <tr>
                <td><b>time</b></td>
                <td><b>remote_addr</b></td>
                <td><b>remote_mac</b></td>
                <td><b>plugins</b></td>
            </tr>";
    */
    foreach($rowarray as $row) {
        $p_id = $row["id"];
        $p_remote_addr = $row["remote_addr"]; // user_agent, remote_addr, remote_mac, time
        $p_remote_mac = $row["remote_mac"];
        $p_user_agent = $row["user_agent"];
        $p_time = $row["time"];
        /*
        echo "<div>";
        echo "<b>time:</b> " . $p_time . "<br>";
        echo "<b>remote_addr:</b> " . $p_remote_addr . "<br>";
        echo "<b>remote_mac:</b> " . $p_remote_mac . "<br>";
        echo "<b>user_agent:</b> " . $p_user_agent . "<br>";
        echo "</div>";
        */
        /*
        echo "<tr>
                <td style='background-color:#DDD; padding-right:10px' nowrap>$p_time</td>
                <td style='background-color:#DDD; padding-right:10px' nowrap>$p_remote_addr</td>
                <td style='background-color:#DDD; padding-right:10px'>$p_remote_mac</td>
                <td style='background-color:#DDD; padding-right:10px'><a href='output.php?type=getplugins&id_details=$p_id'>plugins</a></td>
            </tr>";
        */  
        echo "
            <tr>
                <td style='b-ackground-color:#DDD; padding-right:10px; font-weight: bold;; '>time</td>
                <td style='b-ackground-color:#DDD; padding-right:10px'>$p_time</td>
            </tr>
            <tr>
                <td style='b-ackground-color:#DDD; padding-right:10px; font-weight: bold;'>remote_addr</td>
                <td style='b-ackground-color:#DDD; padding-right:10px'>$p_remote_addr</td>
            </tr>
            <tr>
                <td style='b-ackground-color:#DDD; padding-right:10px; font-weight: bold;'>remote_mac</td>
                <td style='b-ackground-color:#DDD; padding-right:10px'>$p_remote_mac</td>
            </tr>
            <tr>
                <td style='b-ackground-color:#DDD; padding-right:10px; font-weight: bold;'>user_agent</td>
                <td style='b-ackground-color:#DDD; padding-right:10px'>$p_user_agent</td>
            </tr>
            ";
        
    }
    echo "</table>";
    
    $file_db = null;
}

function getPlugins($id_details) {
    global $db_path;
    // Create (connect to) SQLite database in file

    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    //$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT * FROM plugins ORDER BY time DESC;";
    $sql = "SELECT * FROM plugins WHERE id_details = '$id_details';";
    
    $result = $file_db->query($sql);
    
    $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
    
    echo "<table>";
    echo "  <tr>
                <td><b>time</b></td>
                <td><b>name</b></td>
                <td><b>filename</b></td>
                <td><b>version</b></td>
            </tr>";
    foreach($rowarray as $row) {
        $p_name = $row["name"];
        $p_filename = $row["filename"];
        $p_description = $row["description"];
        $p_version = $row["version"];
        $p_time = $row["time"];
        echo "<tr>
            <td style='b-ackground-color:#DDD; padding-right:10px' nowrap>$p_time</td>
            <td style='b-ackground-color:#DDD; padding-right:10px'>$p_name</td>
            <td style='b-ackground-color:#DDD; padding-right:10px'>$p_filename</td>
            <td style='b-ackground-color:#DDD; padding-right:10px'>$p_version</td>
        </tr>";
    }
    echo "</table>";
    
    $file_db = null;
}

function sqlAddPlugins() {
    global $db_path;
    
    // Create (connect to) SQLite database in file
    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                           PDO::ERRMODE_EXCEPTION);
    
    $p_name = $_POST["p_name"];
    $p_filename = $_POST["p_filename"];
    $p_description = $_POST["p_description"];
    $p_version = $_POST["p_version"];
    $p_time = $_POST["p_time"];
    
    $p_name = $_GET["p_name"];
    $p_filename = $_GET["p_filename"];
    $p_description = $_GET["p_description"];
    $p_version = $_GET["p_version"];
    $p_time = $_GET["p_time"];
    
    $sql = "INSERT INTO plugins 
            (name, filename, description, version, time) 
            values 
            ('$p_name', '$p_filename', '$p_description', '$p_version', '$p_time');";
    
    $file_db->exec($sql);
    
    $file_db = null;
}

function fileAction() {
    
    $p_user_agent = $_POST["p_user_agent"];
    $p_remote_addr = $_POST["p_remote_addr"];
    $p_remote_mac = $_POST["p_remote_mac"];
    
    $myFile = "save.txt";
    $fh = fopen($myFile, 'a') or die("can't open file");
    
    $stringData = $p_user_agent . "\n";
    fwrite($fh, $stringData);
    $stringData = $p_remote_addr . "\n";
    fwrite($fh, $stringData);
    $stringData = $p_remote_mac . "\n";
    fwrite($fh, $stringData);
    
    fclose($fh);
}

function searchClient() {
    global $db_path;
    
    $file_db = new PDO("sqlite:$db_path");
    // Set errormode to exceptions
    
    $sql = "SELECT * FROM details;";
    
    $result = $file_db->query($sql);
    
    print_r($result);
}

$type = $_GET["type"];
$id_details = $_GET["id_details"];

echo "<style> html, body { background-color: #EEE; } table, tr, td { font-size: 11px; font-family: monospace, Courier, Arial, Tahoma, Geneva, sans-serif } </style>";

if($type == "getplugins") {
    getPlugins($id_details);
} else if($type == "getclientdetails") {
    getClientDetails($id_details);
} else {
    getDetails();
}
?>