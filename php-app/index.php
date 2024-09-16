<?php

require_once 'config.php';

require_once 'App/controllers/homepageController.php';
require_once 'App/controllers/adminPanelController.php';
require_once 'App/core/Router.php';
require_once 'vendor/autoload.php';

$maxInactiveTime = 1800;
if (isset($_SESSION['last_access'])) {
  $elapsedTime = time() - $_SESSION['last_access'];
  if ($elapsedTime > $maxInactiveTime) {
    session_unset();
    session_destroy();
    header('Location: login.php?message=Session expired');
    exit();
  }
}
$_SESSION['last_access'] = time();

Router::route_request();
