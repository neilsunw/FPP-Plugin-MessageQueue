<?php

include_once "/opt/fpp/www/common.php";
include_once "functions.inc.php";
include_once 'commonFunctions.inc.php';
$pluginName = basename(dirname(__FILE__));  //pjd 7-14-2019   added per dkulp
$pluginVersion ="3.0";
$DEBUG=false;
$myPid = getmypid();

$gitURL = "https://github.com/FalconChristmas/FPP-Plugin-MessageQueue.git";


$pluginUpdateFile = $settings['pluginDirectory'] . "/" . $pluginName . "/" . "pluginUpdate.inc";

//write version number!
WriteSettingToFile("VERSION",urlencode($pluginVersion),$pluginName);


$logFile = $settings['logDirectory']."/".$pluginName.".log";


logEntry("plugin update file: ".$pluginUpdateFile);


if(isset($_POST['updatePlugin']))
{
	$updateResult = updatePluginFromGitHub($gitURL, $branch="master", $pluginName);

	echo $updateResult."<br/> \n";
}


if(isset($_POST['submit']))
{

	WriteSettingToFile("MESSAGE_FILE",urlencode($_POST["MESSAGE_FILE"]),$pluginName);
	
}

sleep(1);

$pluginConfigFile = $settings['configDirectory'] . "/plugin." .$pluginName;
if (file_exists($pluginConfigFile))
	$pluginSettings = parse_ini_file($pluginConfigFile);

$ENABLED = urldecode($pluginSettings['ENABLED']);

$MESSAGE_FILE = urldecode($pluginSettings['MESSAGE_FILE']);

//set a default message queue file
if (trim($MESSAGE_FILE) == "") {
	$MESSAGE_FILE = "/home/fpp/media/config/FPP." . $pluginName . ".db";
	WriteSettingToFile("MESSAGE_FILE",urlencode($_POST["MESSAGE_FILE"]),$pluginName);
}

$db = new SQLite3($MESSAGE_FILE) or die('Unable to open database');

//logEntry("DB: ".$db);


if($db != null) {
	
	createTables();
}

if(isset($_POST['delMessageQueue'])) {
	//delete message queue database
	logEntry("Deleting message queue db file");
	$DELETE_CMD = "/bin/rm ".$MESSAGE_FILE;

	exec($DELETE_CMD);
	
	//touch a new file
	
//	$TOUCH_CMD = "/bin/touch ".$MESSAGE_FILE;
	
//	exec($TOUCH_CMD);
	//create new DB
	$createNewDB_CMD = "/usr/bin/sqlite3 ".$MESSAGE_FILE;
	
	exec($createNewDB_CMD);
	

}



?>

<html>
<head>
</head>

<div id="MessageQueue" class="settings">
<fieldset>
<legend><?php echo $pluginName." Version: ".$pluginVersion;?> Support Instructions</legend>

<p>Known Issues:
<ul>
<li>NONE</li>
</ul>

<p>Configuration:
<ul>
<li>There is no configuration necessary. This plugin supports/allows plugins to communicate and share messages</li>
<li>Current Plugins utilizing MessageQueue:</li>
<li>	SMS Control</li>
<li>	Matrix </li>
<li>	SportsTicker</li>
<li>	Weather</li>
<li>	Election</li>
<li>	Stock Ticker</li>
<li>	RDS To Matrix</li>
<li>	Event Date</li>

</ul>
<p>


<p>To report a bug, please file it against the MessageQueue plugin project on Git: https://github.com/FalconChristmas/FPP-Plugin-MessageQueue
<form method="post" action="http://<? echo $_SERVER['SERVER_ADDR']?>/plugin.php?plugin=<?echo $pluginName;?>&page=plugin_setup.php">


<?

if($DEBUG)
print_r($settings);

$restart=0;
$reboot=0;

echo "ENABLE PLUGIN: ";


PrintSettingCheckbox("Message Queue", "ENABLED", $restart = 0, $reboot = 0, "ON", "OFF", $pluginName = $pluginName, $callbackName = "");


echo "<p/> \n";

echo "Message File Path and Name (/home/fpp/media/config/FPP.FPP-Plugin-MessageQueue.db) : \n";
  
echo "<input type=\"text\" name=\"MESSAGE_FILE\" size=\"64\" value=\"".$MESSAGE_FILE."\"> \n";
echo "<p/> \n";
echo "<hr/> \n";
echo "Message file database \n";
echo "<form name=\"messageManagement\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."?plugin=".$pluginName."&page=plugin_setup.php\"> \n";
echo "<input type=\"submit\" name=\"delMessageQueue\" value=\"Delete Message Queue DB\"> \n";



?>
<p/>
<input id="submit_button" name="submit" type="submit" class="buttons" value="Save Config">
<?
 if(file_exists($pluginUpdateFile))
 {
 	//echo "updating plugin included";
	include $pluginUpdateFile;
}

echo "</form> \n";
?>
</form>
</fieldset>
</div>
<br />
</html>
