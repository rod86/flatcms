<?php

class TwigCMS extends \Twig_Extension {

	public function getName()
	{
		return 'CMS';
	}

	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('cms_menu', array($this, 'cmsMenu'),
				array(
					'is_safe' => array('html'),
					'needs_environment' => true
				)
			),
			new \Twig_SimpleFunction('cms_page_link', array($this, 'pageLink') )			
		);
	}



	public function cmsMenu( \Twig_Environment $twig, $menu = null, $page = null, $depth = null )
	{
		$menu = 'main/about-us/';
		$page = 'about-us';

		$pages = new Pages();
		$m = $pages->get($menu);

		/*if ( empty($page) )
		{
			$page = Frontend::getInstance()->getCurrentPage()->uri;
			var_dump($page);
		}

		$pages = new Pages();

		if ( !empty($menu) )
		{
			$menu = "{$menu}/";
		}
		$p = $pages->isPath($page);

		//$menuPath = $pages->getPagePath($page, $menu);

		
		die();		

		if ( isset($page) && !empty($page) )
		{
			
			var_dump($menuPath);
		}
		else
		{
			$menuPath = $menu;
		}

		$this->_buildMenu($menuPath);	*/	

		//render html
		//return $twig->render('_partials/menu.html', array('foo'=>'hi demo'));
	}

	protected function _buildMenu($menu)
	{
		$pages = new Pages();
		$m = $pages->get( $menu . '/' );

		echo '<pre>';
		print_r($m);
		die();
	}

	public function pageLink()
	{
		
	}
}