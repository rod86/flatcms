<?php

//$config['env'] = '';

// Slim 
$config['slim'] = array(
	'templates.path' => ROOT . '/templates/'
);

// Twig
$config['twig'] = array(
	'charset' => 'utf-8',
   /* 'cache' => realpath('./cache'),*/
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => false
);

// Site
$config['site'] = array(
	'title' => 'Demo site',
	'url' => '',
	'theme' => '', //later
);

return $config;
	