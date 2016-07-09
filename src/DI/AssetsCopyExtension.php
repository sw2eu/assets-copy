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

		foreach ($config['dirs'] as $dir) {
			/** @var \SplFileInfo $file */
			foreach (Finder::findFiles($config['mask'])->from($dir) as $file) {
				$filename = substr($file->getPathname(), strlen($dir) + 1);
				$destFile = "$wwwDir/$destination/$filename";

				@mkdir(dirname($destFile), 0777, TRUE);
			    copy($file->getPathname(), $destFile);
			}
		}
	}
}
