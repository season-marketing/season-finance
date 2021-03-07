<?php

namespace LandingPage\Blade;

use eftec\bladeone\BladeOne;

class View
{
	private $blade = null;
	private $path = __DIR__ . '/../Resources/';

	function __construct(string $template, array $data)
	{
		$this->blade = new BladeOne($this->path . "views", BladeOne::MODE_AUTO);
		echo $this->blade->run('index', $data);
	}
}

