<?php

    // Helpers
    include_once 'functions.php';

    // Default ports to check
    $ports = array(22, 25, 80, 443, 587, 993, 5222, 5269);

    // Get variables
    $host = (!empty($_GET['host'])) ? $_GET['host'] : NULL;
    $eport = (!empty($_GET['eport'])) ? $_GET['eport'] : NULL;
    if ($eport) {
        $ports[] = $eport;
    }

    // Output format
    $allowed_formats = array('html', 'json');
    $format = 'html'; // Default format
    if (!empty($_GET['format']) && in_array($_GET['format'], $allowed_formats)) {
        $format = $_GET['format'];
    }

    // Ports categories
    $web = array(80, 443);
    $ssh = array(22);
    $xmpp = array(5222, 5269);
    $email = array(25, 587, 993);

    // Get port status if asked for
    $ports_status = NULL;
    if (!empty($host)) {
        $ports_status = ports_status($ports, $host);
    }

    // Output JSON if asked for
    if ($format == 'json' && !empty($ports_status)) {
        header('Content-Type: application/json');
        print json_encode($ports_status);
        exit;
    }

?><!DOCTYPE html>
<html>
 <head>
    <meta charset='UTF-8'>
    <title>YunoPorts – Check opened ports on YunoHost instance</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />
 </head>
<body>
    <header>
        <h1>YunoPorts</h1>
        <p>Check if ports are open on YunoHost instance.</p>
    </header>

    <p class="doc">
        <a href='https://yunohost.org/#/isp_box_config'>Ports documentation</a>
    </p>

    <form method='GET' class="pure-form">
        <p>
            <label for="host">Domain name or IP address:</label>
            <input type='text' name='host' id='host' value='' />
        </p>
        <p>
            <label for="eport">Extra port:</label>
            <input type='number' name='eport' id='eport' min="0" max="65535" value='' />
        </p>
        <p>
            <input type='submit' name='submit' value='Check' />
        </p>
    </form>
    
    <?php if ($format == 'html' && !empty($ports_status)): ?>
    <div class="results">
    <h2><a href='https://<?php print $host; ?>'><?php print $host; ?></a></h2>
    <ul>
        <li>Web: 
        <?php foreach ($web as $web_port): ?>
            <?php display_status($web_port, $ports_status); ?>
        <?php endforeach; ?>
        </li>
        <li><a href='https://yunohost.org/#/ssh'>SSH:</a> 
        <?php foreach ($ssh as $ssh_port): ?>
            <?php display_status($ssh_port, $ports_status); ?>
        <?php endforeach; ?>
        </li>
        <li><a href='https://yunohost.org/#/XMPP'>XMPP:</a> 
        <?php foreach ($xmpp as $xmpp_port): ?>
            <?php display_status($xmpp_port, $ports_status); ?>
        <?php endforeach; ?>
        </li>
        <li><a href='https://yunohost.org/#/email'>Email:</a> 
        <?php foreach ($email as $email_port): ?>
            <?php display_status($email_port, $ports_status); ?>
        <?php endforeach; ?>
        </li>
        <?php if ($eport): ?>
        <li>Extra: 
            <?php display_status($eport, $ports_status); ?>
        </li>
        <?php endif; ?>
    </ul>
    </div>
    <?php endif; ?>

 </body>
</html>
