<?php

namespace Quetzal;

/**
 * Автозагрузчик для пространства Quetzal
 *
 * Class AutoLoader
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal
 */
class AutoLoader
{
	static private $recursiveSearch = true;

	public function __construct()
	{}

	/**
	 * @return string
	 */
	protected static function getBasePath()
	{
		return __DIR__;
	}

	/**
	 * @param string $path
	 * @param string $file
	 *
	 * @return string
	 */
	protected static function generateFilePath($path, $file)
	{
		return str_replace('/Quetzal/', '/', sprintf('%s/%s.php', $path, str_replace('\\', '/', $file)));
	}

	/**
	 * @param string $file
	 */
	public static function autoLoad($file)
	{
		$path = self::getBasePath();
		$filePath = self::generateFilePath($path, $file);

		if (file_exists($filePath)) {
			require_once($filePath);
		} else {
			self::$recursiveSearch = true;

			self::recursiveLoad($file, $path);
		}
	}

	/**
	 * @param string $file
	 * @param string $path
	 */
	public static function recursiveLoad($file, $path)
	{
		if (false !== ($handle = opendir($path)) && self::$recursiveSearch) {
			while (false !== ($dir = readdir($handle)) && self::$recursiveSearch) {
				if (strpos($dir, '.') === false) {
					$path2 = $path . '/' . $dir;
					$filePath = $path2 . '/' . $file . '.php';

					if (file_exists($filePath)) {
						self::$recursiveSearch = false;

						require_once($filePath);

						break;
					}

					self::recursiveLoad($file, $path2, self::$recursiveSearch);
				}
			}

			closedir($handle);
		}
	}
}
