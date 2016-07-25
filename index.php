<!DOCTYPE html>
<html>
 <head>
  <meta charset='UTF-8'>
  <title>YunoPorts – Check opened ports on YunoHost instance</title>
  <link rel="icon" type="image/png" href="img/greendot.png" />
 </head>
<body>
<h2>YunoPort – Check if ports are open on YunoHost instance</h2>
 <form method='GET'>
 Domain name or IP address:
 <input type='text' name='host' value='' / >
 <input type='submit' name='submit' value='OK' />
</form>

<?php
$host = $_GET['host'];
$ports = array(22, 25, 53, 80, 443, 465, 587, 993, 5222, 5269);

function open_port($host, $port)
{
	$fp = fsockopen($host, $port, $errno, $errstr, 0.1);
	@fclose($fp);
	return ($errno === 0);
}

function display_status($port, $ports_status)
{
	if ($ports_status[$port] != 1)
		echo "<img src='img/reddot.png' height='20px'>";
	else
		echo "<img src='img/greendot.png' height='20px'>";
	echo "$port, ";
}

if ($_GET['submit'] !== 'OK')
	return;
echo "<a href='https://yunohost.org/#/isp_box_config'>Ports documentation</a><br />";
echo "<strong><a href='https://$host'>$host</a></strong>:<br />";
foreach($ports as $key => $port)
{
	if (open_port($host, $port) != "1")
		$ports_status[$port] = false;
	else
		$ports_status[$port] = true;
}
$web = array(80, 443);
$xmpp = array(5222, 5269);
$email = array(25, 465, 587);
echo "<ul><li>Web: ";
foreach($web as $port)
	display_status($port, $ports_status);
echo "</li><li><a href='https://yunohost.org/#/ssh'>SSH:</a> ";
display_status(22, $ports_status);
echo "</li><li><a href='https://yunohost.org/#/XMPP'>XMPP:</a> ";
foreach($xmpp as $port)
	display_status($port, $ports_status);
echo "</li><li><a href='https://yunohost.org/#/email'>Email:</a> ";
foreach($email as $port)
	display_status($port, $ports_status);
echo "</li><li>DNS: ";
display_status(53, $ports_status);
echo "</li></ul>";
?>

 </body>
</html>
