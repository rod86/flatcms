<?php

/**
* Generic content pages
**/

class Pages {

	protected $basePath;

	protected $dir = 'menus';

	protected $extension = '.md';

	protected $hidden = '.';

	public function __construct()
	{
		$this->basePath = CONTENT_DIR . $this->dir .'/';
	}

	public function getExtension()
	{
		return $this->extension;
	}

	public function getBasePath()
	{
		return $this->basePath;
	}

	// Get pages in a dir
	public function get($dir = '')
	{		
		$path = $this->basePath;

		if ($dir) $path .= $dir;

		$items = array();

		if (is_dir($path))
		{
		    if ($dh = opendir($path))
		    {
		        while (($file = readdir($dh)) !== false)
		        {
		        	if (in_array($file, array('.','..','.DS_Store')) || is_dir($path . $file)) continue;		        	

		        	if ($this->isHidden($file)) continue;

		        	$item = $this->parseContent($dir . $file, true);
		      		
		      		$name = basename($file, $this->extension);
		        	
		        	if (is_dir("{$this->basePath}{$dir}{$name}/"))
    					$item['children'] = $this->get($dir . $name . '/');	
					    		
		        	$order = $item['order'];
		        	$items[$order] = $item;		        		            
		        }
		        closedir($dh);
		    }
		}

		ksort($items);

		return $items;
	}

	// checks if the file is valid (file exists and not hidden)
	public function check($file)
	{
		if (!$path = $this->isPath($file)) return false;

		if ($this->isHidden($file)) return false;

		return $path;
	}

	public function getMenus()
	{
		$menus = array();

		// check that page exists in menus		
	    if ($dh = opendir($this->basePath))
	    {
	        while (($dir = readdir($dh)) !== false)
	        {
	        	if ( !in_array($dir, array('.','..','.DS_Store')) && is_dir($this->basePath . $dir) )
	        	{
	        		if (file_exists($this->basePath . $dir . '/'))
	        		{
	        			$menus[] = $dir;
	        		}
	        		
	        	}
	        }
	    }
	    closedir($dh);
	    
	    return $menus;
	}

	public function getPagePath($page, $path = '')
	{
		$basePath = ( empty($path) )?$this->basePath:$this->basePath.$path;

		// check that page exists in menus		
	    if ($dh = opendir($basePath))
	    {
	        while (($file = readdir($dh)) !== false)
	        {	        	
	        	if ( !in_array($file, array('.','..','.DS_Store')) )
	        	{
	        		$name = basename($file, $this->extension);
	        		
	        		if ($name == $page)
	        		{
	        			return $path.$name;
	        		}

	        		if ( is_dir($basePath . $file) )
	        		{
	        			if ( $page = $this->getPagePath($page, $path . $file . '/') )
	        				return $page;
	        		}	        		
	        	}
	        }
	    }
	    closedir($dh);

	    return false;
	}

	// checks if the page path exists under any menu
	public function isPath($file)
	{
		// check that page exists in menus		
	    if ($dh = opendir($this->basePath))
	    {
	        while (($dir = readdir($dh)) !== false)
	        {
	        	if ( !in_array($dir, array('.','..','.DS_Store')) && is_dir($this->basePath . $dir) )
	        	{
	        		if (file_exists($this->basePath . $dir . '/' . $file))
	        		{
	        			return $dir . '/' . $file;
	        		}
	        		
	        	}
	        }
	    }
	    closedir($dh);

	    return false;
	}

	public function isHidden($file)
	{
		$start = substr( $file, 0, strlen($this->hidden) );		        	
		if ( $start == $this->hidden ) return true;
		else return false;
	}

	// parse content
	public function parseContent($file, $skipContent = false)
	{
		$page = new FileParser();
    	$page->skipContent = $skipContent;
		$item = $page->parse($this->basePath . $file);

		if (!$item) return false;

		return $this->parseFields($item, $file);
	}

	//parse fields values
	public function parseFields($item, $file)
	{
		$name = basename($file, $this->extension);

		$menus = $this->getMenus();

    	$item['url'] = '/' . str_replace(array($this->basePath, $this->extension), array('','/'), $file);  
    	$item['url'] = str_replace($menus, '', $item['url']);
    	$item['url'] = str_replace('//', '/', $item['url']);

    	$item['is_active'] = ( $name == Frontend::getInstance()->getCurrentPage()->urlSegment )?true:false;
    	$item['is_active_parent'] = ( !$item['is_active'] && in_array($name, Frontend::getInstance()->getCurrentPage()->parents) )?true:false;
    		    
    	return $item;
	}
}