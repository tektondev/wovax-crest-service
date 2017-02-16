<?php require_once 'config.php';?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
	<div class="wrap">
		<div class="site-title">Sotheby's Crest Feed</div>
   	</div>
</header>
<main>
	<div class="wrap">
    <h1>Setup Instructions</h1>
    <ol class="setup">
    	<li>Unzip Files and Install on Server</li>
        <li>Import & Setup Database (<a href="<?php echo CRESTAPPBASEURL;?>resources/crest_properties.sql">Download</a>)</li>
        <li>Edit the Config File</li>
        <li>Pre-Load Properties (<a href="<?php echo CRESTAPPBASEURL;?>properties/preload">Here)</a></li>
        <li>Setup CRON Job to Fire Every Minute (.../cron.php)</li>
    </ol>
    </div>
</main>
<body>
</body>
</html>