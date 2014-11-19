<?php

// Projects	
$app->get('/projects-aaaa/', function() use ($app)
{		
	$template = 'page';

	echo 'projects';

	$app->render("{$template}.html", array());
});

// Generic page
$app->get('/(:page+(/?))', function() use ($app)
{
	$pages = new Pages();
	$page = $pages->parseContent(Frontend::getInstance()->getCurrentPage()->file);

	$template = ( isset($page['template']) && !empty($page['template']) )?$page['template']:'page';

	$app->render("{$template}.html", array('page' => $page));		
});
