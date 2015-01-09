<?php
//$DEBUG=true;

include_once "/opt/fpp/www/common.php";
include_once "functions.inc.php";
$pluginName = "MessageQueue";

//$DEBUG=true;

if(isset($_POST['submit']))
{


	WriteSettingToFile("ENABLED",urlencode($_POST["ENABLED"]),$pluginName);
	WriteSettingToFile("MESSAGE_FILE",urlencode($_POST["MESSAGE_FILE"]),$pluginName);
	

}
$ENABLED = urldecode(ReadSettingFromFile("ENABLED",$pluginName));

$MESSAGE_FILE = urldecode(ReadSettingFromFile("MESSAGE_FILE",$pluginName));


if(trim($MESSAGE_FILE) == "") {
	$MESSAGE_FILE = "/tmp/FPP.MessageQueue";
}

?>

<html>
<head>
</head>

<div id="MessageQueue" class="settings">
<fieldset>
<legend>MessageQueue Support Instructions</legend>

<p>Known Issues:
<ul>
<li>NONE</li>
</ul>

<p>Configuration:
<ul>
<li>There is no configuration necessary. This plugin supports/allows plugins to communicate and share messages</li>
<li>Current Plugins utilizing MessageQueue: SMS Control, Matrix, SportsTicker</li>
</ul>
<p>


<p>To report a bug, please file it against the MessageQueue plugin project on Git: https://github.com/LightsOnHudson/FPP-Plugin-MessageQueue
<form method="post" action="http://<? echo $_SERVER['SERVER_NAME']?>/plugin.php?plugin=<?echo $pluginName;?>&page=plugin_setup.php">


<?

$restart=0;
$reboot=0;

echo "ENABLE PLUGIN: ";

if($ENABLED== 1 || $ENABLED == "on") {
		echo "<input type=\"checkbox\" checked name=\"ENABLED\"> \n";
//PrintSettingCheckbox("Radio Station", "ENABLED", $restart = 0, $reboot = 0, "ON", "OFF", $pluginName = $pluginName, $callbackName = "");
	} else {
		echo "<input type=\"checkbox\"  name=\"ENABLED\"> \n";
}




echo "<p/> \n";


echo "<p/> \n";

echo "Message File Path and Name (/tmp/FPP.MessageQueue) : \n";
  
echo "<input type=\"text\" name=\"MESSAGE_FILE\" size=\"16\" value=\"".$MESSAGE_FILE."\"> \n";
 

?>
<p/>
<input id="submit_button" name="submit" type="submit" class="buttons" value="Save Config">

</form>
</fieldset>
</div>
<br />
</html>