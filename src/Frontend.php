<?php

require ROOT . 'src/FileParser.php';
require ROOT . 'src/Pages.php';
require ROOT . 'src/TwigCMS.php';

class Frontend {

	public static $instance;

	protected $slim;	//slim instance

	protected $currentPage;

	protected $menus;

	protected $siteConfig;

	public function __construct( $config )
	{
		static::$instance = $this;

		$this->siteConfig = $config['site'];

		$this->slim = \Slim\Slim::getInstance();	

		$this->loadCurrentPage();

		//$this->loadMenus();
	
		$data = array(
			'site' => $this->siteConfig,
			'menu' => $this->menus,
			'theme' => array(
				'assets' => '/assets/',
				'path' => '/templates/'				
			)
		);

		$this->slim->view()->setData($data);
	}

	public static function getInstance()
	{
		return static::$instance;
	}

	public function getCurrentPage()
	{
		return $this->currentPage;
	}
	
	public function loadCurrentPage()
	{
		$uri = $this->slim->request()->getPathInfo();
		$parents = $params = array_filter( explode('/', $uri) );
		
		if (!$params)
		{
			$currentPage = $uri = 'home';			
		} 
		else
		{			
			$currentPage = array_pop($params);
		}

		$pages = new Pages();

		$file = trim($uri, '/') . $pages->getExtension();		

		if ( !$file = $pages->check($file) ) $this->slim->notFound();

		$this->currentPage = (object) array(
			'urlSegment' => $currentPage,
			'uri' => $uri,
			'file' => $file,
			'parents' => $params
		);
	}

	public function loadMenus()
	{
		$pages = new Pages();

		$menus = $pages->getMenus();

		foreach ($menus as $menu)
		{
			$this->menus[$menu] = $pages->get( $menu . '/' );
		}
	}
}