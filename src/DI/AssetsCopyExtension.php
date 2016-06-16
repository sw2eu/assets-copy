<?php

namespace Sw2\AssetsCopy\DI;

use Nette;
use Nette\Utils\Finder;

/**
 * Class LoadFontExtension
 *
 * @package Sw2\LoadFont\DI
 */
class AssetsCopyExtension extends Nette\DI\CompilerExtension
{
	/** @var array */
	public $defaults = [
		'debugger' => FALSE,
		'dest' => 'webtemp',
		'dirs' => [],
	];

	/**
	 * Processes configuration data. Intended to be overridden by descendant.
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);
		$wwwDir = $builder->parameters['wwwDir'];
		$destination = $config['dest'];

		if (!is_writable("$wwwDir/$destination")) {
			throw new Nette\IOException("Directory '$wwwDir/$destination' is not writable.");
		}

		/** @var \SplFileInfo $file */
		foreach (Finder::findFiles($config['mask'])->from($config['dirs']) as $file) {
			$filename = $file->getFilename();
			copy($file->getPathname(), "$wwwDir/$destination/$filename");
		}
	}
}
