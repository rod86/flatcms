<?php

define('ROOT', str_replace(basename(__DIR__), '', dirname(__FILE__)));
define('CONTENT_DIR', ROOT . 'content/');
define('APP_DIR', ROOT . 'app/');

require ROOT . 'vendor/autoload.php';
require ROOT . 'src/Frontend.php';

// Load config
$config = require APP_DIR . 'config.php';

$app = new \Slim\Slim($config['slim']);

// Twig settings
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = $config['twig'];
$app->view->parserExtensions = array(
	new \Slim\Views\TwigExtension(),
	new TwigCMS()
);

// Boot Frontend common parts as current page, menus, ...
$app->hook('slim.before.dispatch', function() use ($app, $config)
{	
	$app->Frontend = new Frontend($config);
});

// Routes
include APP_DIR . 'routes.php';

$app->run();



