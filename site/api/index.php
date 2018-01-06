<?php
/**
 *  @author	Rafael Vinicius Barros Ferreira
 */

require 'vendor/autoload.php';
require 'config/constants.php';
require 'config/config.php';

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

require 'app/routes.php';

$app->run();

?>
