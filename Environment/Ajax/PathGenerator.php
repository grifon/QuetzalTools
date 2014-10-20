<?php

namespace Quetzal\Environment\Ajax;

/**
 * Генератор путей для обработчиков AJAX-запросов
 *
 * Class PathGenerator
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Environment\Ajax
 */
class PathGenerator
{
	/**
	 * Каталог, в котором хранятся обработчики
	 */
	const BASE_DIR = '/local/ajax';

	/**
	 * @var string
	 */
	protected $basePath = '';

	/**
	 * @param string $basePath Абсолютный путь к корню сайта
	 */
	public function __construct($basePath = '')
	{
		$basePath = str_replace('\\', '/', $basePath);

		$this->basePath = substr($basePath, -1) == '/' ? substr($basePath, 0, strlen($basePath) - 1) : $basePath;
	}

	/**
	 * Генерирует относительный путь к обработчику
	 *
	 * @param string $name
	 * @param string $siteId
	 *
	 * @return string
	 */
	public function getRelativePath($name, $siteId = '')
	{
		return sprintf(
			'%s/%s%s.php',
			self::BASE_DIR,
			sprintf('%s/', $siteId ?: 'common'),
			$name
		);
	}

	/**
	 * Получает полный путь к обработчику
	 *
	 * @param string $name
	 * @param string $siteId
	 *
	 * @return string
	 */
	public function getFullPath($name, $siteId = '')
	{
		return sprintf(
			'%s%s',
			$this->basePath,
			$this->getRelativePath($name, $siteId)
		);
	}
} 