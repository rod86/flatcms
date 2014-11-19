<?php

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use \Michelf\Markdown;

class FileParser {

	protected $fileContent;

	protected $page;

	public $skipContent = false;

	public function parse($file)
	{
		$fileContent = file_get_contents($file);
		if (empty($fileContent)) return array();
		$this->fileContent = array_filter(preg_split('/[\n]*[-]{3}[\n]/', $fileContent, 3));

		$this->parseYaml();
		if (!$this->skipContent) $this->parseBlocks();

		return $this->page;
	}

	protected function parseYaml()
	{
		try
		{
			$text = (isset($this->fileContent[1]))?$this->fileContent[1]:'';
		    $this->page = Yaml::parse($text);
		}
		catch (ParseException $e)
		{
		    printf("Unable to parse the YAML string: %s", $e->getMessage());
		}
	}

	protected function parseBlocks()
	{
		$contentBlocks = $this->fileContent[2];

		preg_match_all('/{% block(.+?)%}/mis', $contentBlocks, $matches);

		$content = array();
		foreach ($matches[1] as $value)
		{
			$value = trim($value);

			preg_match('/{% block '.$value.' %}(.+?){% endblock %}/mis', $contentBlocks, $match);

			$this->page[$value] = Markdown::defaultTransform(trim($match[1]));
		}
	}
}