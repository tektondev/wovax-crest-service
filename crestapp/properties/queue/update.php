<?php

require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/config.php';
require_once CRESTAPPPATH . 'endpoints/properties-queue-update.endpoint.class.php';

$endpoint = new Properties_Queue_Update_Endpoint();

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head> 
<body>
<?php $endpoint->do_request(); ?>
</body>
</html>