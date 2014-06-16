<?php

/**
 * Хелпер для работы с окружением - конфигурацией для сайта
 *
 * Class EnvironmentHelper
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 */
class EnvironmentHelper
{
	/**
	 * Хранилице соответствий id сайта => имя файла с конфигурацией
	 *
	 * @var array
	 */
	private static $siteConfigMap = array();

	/**
	 * key-value хранилище различных конфигурационных констант
	 *
	 * @var array
	 */
	private static $storage = array();

	/**
	 * Устанавливает соответствия имен файлов с конфигурацией сайта
	 * к id сайта
	 *
	 * @param array $map
	 */
	public static function setConfigInitializersMap(array $map)
	{
		self::$siteConfigMap = $map;
	}

	/**
	 * Возвращает имя файла с инициализатором конфигурации для конкретного сайта
	 *
	 * @param $siteId
	 *
	 * @return string
	 */
	public static function getConfigInitializerFileNameForSite($siteId)
	{
		return @self::$siteConfigMap[$siteId];
	}

	/**
	 * Устанавливает список конфигурационных параметров
	 *
	 * @param array $config
	 */
	public static function setConfiguration(array $config)
	{
		self::$storage = $config;
	}

	/**
	 * Возвращает параметр конфигурации
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function getParam($key)
	{
		return @self::$storage[$key];
	}

	/**
	 * Устанавливает параметр конфигурации
	 *
	 * @param string $key
	 * @param $value
	 */
	public static function setParam($key, $value)
	{
		self::$storage[$key] = $value;
	}
}
